<template>
  <div class="welcome-container">
    <div class="welcome-left">
      <div class="welcome-textbox">
        <div class="welcome-title">{{ getTimeBasedTitle }}</div>
        <div class="welcome-subtitle">{{ $t('login.subtitle') }}</div>
        <div class="welcome-author">{{ $t('login.imageAuthor', { author: 'wesleyjharrison', platform: 'pixabay'}) }}</div>
      </div>
    </div>
    <div class="welcome-right">
      <h3>{{ $t('login.formHeader') }}</h3>

      <form>
        <div class="form-group required">
          <label>{{ $t('login.username.label') }}</label>
          <input type="text" class="form-control" :placeholder="$t('login.username.placeholder')">
        </div>
        <div class="form-group required">
          <label>{{ $t('login.password.label') }}</label>
          <input type="password" class="form-control" :placeholder="$t('login.password.placeholder')">
        </div>
        <div class="clearfix">
          <button type="submit" class="btn btn-primary float-right">{{ $t('login.signIn') }}</button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  data: function () {
    return {
      time: new Date().getHours()
    };
  },
  computed: {
    getTimeBasedTitle: function () {
      if (this.time >= 5 && this.time < 12) {
        return this.$t('login.title.morning');
      }

      if (this.time >= 12 && this.time < 15) {
        return this.$t('login.title.noon');
      }

      if (this.time >= 15 && this.time < 18) {
        return this.$t('login.title.afternoon');
      }

      if (this.time >= 18 && this.time <= 23) {
        return this.$t('login.title.evening');
      }

      return this.$t('login.title.other');
    }
  }
};
</script>
