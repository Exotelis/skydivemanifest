<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntIdRequest;
use App\Http\Requests\Text\CreateRequest;
use App\Http\Requests\Text\UpdateRequest;
use App\Models\Text;
use App\Models\Waiver;
use App\Traits\Paginate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Class TextController
 * @package App\Http\Controllers
 */
class TextController extends Controller
{
    use Paginate;

    /**
     * Get a list of all texts of a related model.
     *
     * @param Request $request
     * @param Waiver  $model
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function all(Request $request, $model)
    {
        $this->validatePagination($request->only('limit', 'page'));

        $texts = QueryBuilder::for($model->texts())
            ->allowedFilters(\App\Filters\TextFilters::filters())
            ->allowedSorts(\App\Filters\TextFilters::sorting())
            ->defaultSort('language_code', 'position')
            ->paginate($request->input('limit'));

        return response()->json($texts);
    }

    /**
     * Create a new text for a related model.
     *
     * @param CreateRequest $request
     * @param Model|Waiver  $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request, Model $model)
    {
        $this->protectFromChanges($model);

        $input = $request->only(['language_code', 'position', 'text', 'title']);
        $text = null;

        try {
            DB::beginTransaction();

            // Determine sort number or reorder already existing elements
            $input['position'] = $this->determinePosition(
                $model,
                $request->input('language_code'),
                $request->input('position')
            );

            // Create new text
            /** @var Text $text */
            $text = $model->texts()->create($input);

            // Reposition texts of the same model type and language
            Text::reposition($text->id, $text->textable_id, $text->textable_type, $text->language_code)
                ->where('position', '>=', $input['position'])
                ->increment('position');
            $this->logReposition();

            DB::commit();
        } catch (\Throwable $exception) {
            abort(500, __('messages.text_created_failed'));
        }

        return response()->json(['message' => __('messages.text_created'), 'data' => $text], 201);
    }

    /**
     * Delete a single text of a related model.
     *
     * @param Request $request
     * @param Waiver  $model
     * @param Text    $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $model, Text $text)
    {
        $this->protectFromChanges($model);

        try {
            DB::beginTransaction();

            // Reposition texts of the same model type and language
            Text::reposition($text->id, $text->textable_id, $text->textable_type, $text->language_code)
                ->where('position', '>', $text->position)
                ->decrement('position');
            $this->logReposition();

            // Delete text
            $text->delete();

            DB::commit();
        } catch (\Exception $e) {
            abort(500, __('error.500'));
        }

        return response()->json(['message' => trans_choice('messages.deleted_texts', 1)]);
    }

    /**
     * Delete one or more texts of a related model.
     *
     * @param IntIdRequest $request
     * @param Waiver       $model
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteBulk(IntIdRequest $request, $model)
    {
        $this->protectFromChanges($model);

        $input = $request->only(['ids']);
        $count = 0;

        foreach ($model->texts as $text) {
            if (\in_array($text->id, $input['ids']) && $text->delete()) {
                $count++;
            }
        }

        return response()->json([
            'count'   => $count,
            'message' => trans_choice('messages.deleted_texts', $count)
        ]);
    }

    /**
     * Return a single text of a related model.
     *
     * @param Request $request
     * @param Waiver  $model
     * @param Text    $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(Request $request, $model, Text $text)
    {
        return response()->json($text->load(['textable']));
    }

    /**
     * Update a text of a related model.
     *
     * @param UpdateRequest $request
     * @param Waiver        $model
     * @param Text          $text
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, $model, Text $text)
    {
        $this->protectFromChanges($model);

        $input = $request->only(['position', 'text', 'title']);

        try {
            DB::beginTransaction();

            // Determine sort number or reorder already existing elements
            $input['position'] = $this->determinePosition(
                $model,
                $text->language_code,
                $request->input('position'),
                'update'
            );

            // Reposition texts of the same model type and language
            if ($input['position'] > $text->position) {
                Text::reposition($text->id, $text->textable_id, $text->textable_type, $text->language_code)
                    ->where('position', '<=', $input['position'])
                    ->where('position', '>', $text->position)
                    ->decrement('position');
            }
            if ($input['position'] < $text->position) {
                Text::reposition($text->id, $text->textable_id, $text->textable_type, $text->language_code)
                    ->where('position', '>=', $input['position'])
                    ->where('position', '<', $text->position)
                    ->increment('position');
            }
            $this->logReposition();

            // Update model
            $text->update($input);

            DB::commit();
        } catch (\Throwable $exception) {
            abort(500, __('error.500'));
        }

        return response()->json($text);
    }

    /**
     * Determine the sort number of the created or updated model.
     *
     * @param Waiver   $model
     * @param string   $languageCode
     * @param int|null $position
     * @param string   $method create|update
     * @return int
     */
    protected function determinePosition($model, $languageCode, $position, $method = 'create')
    {
        $max = $model->texts()->where('language_code', $languageCode)->max('position');
        $position = (int) $position;

        if ($position <= 0 || $position > $max) {
            return $method === 'create' ? $max + 1 : $max;
        }

        return $position;
    }

    /**
     * Log message when affected models have been repositioned
     *
     * @return void
     */
    protected function logReposition()
    {
        Log::info("[Text] The 'position' attribute of affected rows have been updated.");
    }

    /**
     * Check if the related model has an is_active attribute. If it has one and it's active, abort!
     *
     * @param Model $relatedModel
     */
    protected function protectFromChanges(Model $relatedModel) {
        if (! \array_key_exists('is_active', $relatedModel->getAttributes())) {
            return;
        }

        if ($relatedModel->is_active) {
            abort(400, __('error.is_active_cannot_be_changed'));
        }
    }
}
