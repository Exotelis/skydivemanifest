<template>
  <main id="welcome">
    <slot><router-view></router-view></slot>
  </main>
</template>

<script>
export default {
  name: 'WelcomeLayout'
};
</script>
