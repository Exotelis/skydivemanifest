# Contribution guidelines
Contributions are very much **appreciated**

We accept contributions via Pull Requests on [GitHub](https://github.com/Exotelis/skydivemanifest)

## Commits
Each commit has an associated commit message that is stored in git. The first line of the commit
message is the [commit title](#commit-title). The second line should be left blank. The lines that
follow constitute the [commit message](#commit-message).

A commit and its message should be focused around a particular change.

### Commit title
The text up to the first empty line in a commit message is the commit title. It should be a single
short line of at most **72 characters**, summarizing the change, and prefixed with the component
you are changing and the action you perform. 

```
<component>: <action> - Title
```

Valid components are **core**, **restapi** and **admin**

The action is optional but should be used in the following scenarios:
- **add** - When it adds a new feature
- **update** - When it adds and removes a feature
- **remove** - When it removes a feature
- **fix** - When it fixes a bug

Examples:
```
core: add - How to write commit messages
admin: fix - Login is working again
restapi: Some cleanup work
```

**Note:** There are two special cases. The first time you add a new file, always commit it right
away without making any changes. If the file was auto generated, keep it as it is. Also when
deleting files that are no longer needed. The commit title must then be one of the following:
```
core: initial commit
restapi: last commit
```
If you added or removed more than one file, you can put them in a single commit.

When you have to move a file use `git mv <sourcepath> <targetpath>`. The commit message should then look like:
```
admin: move file
```
If you move more then one file use `move files` instead.

### Commit message
In the body of your commit message, be as specific as possible. If the commit message title was too
short to fully state what the commit is doing, use the body to explain not just the "what", but
also the "why". Each line of the body should be at most **72 characters** long.

Example:
```
core: add - How to contribute

Adds the section commits and pull requests to make sure that any
contributor can follow our guidelines.
```

**Note:** When you add or delete more than one file, list all files in the commit message.
```
admin: last commit

login.html
login.js
```

### Fixes line(s)
If the commit fixes one or more issues tracked by the [issue tracker](https://github.com/Exotelis/skydivemanifest/issues)
add a Fixes: line (or lines) to the commit message - for example:
```
Fixes: https://github.com/Exotelis/skydivemanifest/issues/1
```
This line should be added just before the `Signed-off-by:` line

### Signed-off-by
Sign-off every of your commits by running the `git commit` command with the option `-s`. This
should be the very last line of your commits message.
```
Signed-off-by: Sebastian Krah <exotelis@mailbox.org>
```

### Example
Here is an example showing a properly-formed commit message:
```
core: add - How to contribute

Adds the section commits and pull requests to make sure that any
contributor can follow our guidelines.

Fixes: https://github.com/Exotelis/skydivemanifest/issues/1
Signed-off-by: Sebastian Krah <exotelis@mailbox.org>
```

## Pull requests
Please open one pull request per feature. Create feature branches, we won't pull any changes from
your master branch. Also document any change in behavior, any relevant documentation must be kept
up-to-date. Please make sure to add tests for your changes.

### Pull request title
As for the [commit title](#commit-title), the component should also be the start of you Pull
request title. After the component you can either use the title of the most relevant commit or if
you have just a single commit the title of that one. If you want to be more specific you can also
write a complete new title. If the Pull request fixes one or more issues tracked by the
[issue tracker](https://github.com/Exotelis/skydivemanifest/issues) place a list of issue ids in
brackets. For example:
```
admin: The 'Add an aircraft' feature has been fixed (23, 52)
```

### Pull request description
In addition to a title, the PR also has a description field, or "body".

The PR description is a place for summarizing the PR as a whole. It need not duplicate information
that is already in the commit messages. It can contain notices to maintainers, links to tracker
issues and other related information, to-do lists, screenshots, etc. The PR title and description
should give readers a high-level notion of what the PR is about, quickly enabling them to decide
whether they should take a closer look.
