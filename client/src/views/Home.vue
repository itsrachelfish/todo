<template>
  <div class="home">
    <div class="text-xl mb-10">
      {{ timerOutput }}
    </div>

    <div v-if="state == 'stopped'">
      <span class="bg-green-600 text-white font-bold p-4 rounded-xl">Start Timer</span>
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

  computed: {
    timerOutput() {
      let timeRemaining = this.time;
      let milliseconds = 0;
      let seconds = 0;
      let minutes = 0;
      let hours = 0;

      if(timeRemaining > 0) {
        milliseconds = timeRemaining % 1000;
        timeRemaining -= milliseconds;
      }

      if(timeRemaining > 0) {
        seconds = (timeRemaining / 1000) % 60;
        timeRemaining -= seconds * 1000;
      }

      if(timeRemaining > 0) {
        minutes = (timeRemaining / (1000 * 60)) % 60;
        timeRemaining -= minutes * 1000 * 60;
      }

      if(timeRemaining > 0) {
        hours = timeRemaining / (1000 * 60 * 60);
      }

      return `${hours}h ${minutes}m ${seconds}s`;
    },
  }
}
</script>
