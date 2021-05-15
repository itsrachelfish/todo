<template>
  <div class="home">
    <div v-if="state == 'stopped'">
      Start Timer

      <span class="bg-red-600 p-4 rounded-xl">test</span>
    </div>

    <div v-else-if="state == 'running'">
      Pause Timer
    </div>

    <div v-else-if="state == 'paused'">
      Reset Timer
      Save Time
    </div>

    Add Time Manually
    Today
    History
  </div>
</template>

<script>
export default {
  name: 'Home',

  data() {
    return {
      time: 0,
      state: 'stopped',
    }
  },
}
</script>
