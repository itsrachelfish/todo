<template>
  <div class="home">
    <div class="text-xl mb-4">
      {{ timerOutput }}
    </div>

    <div v-if="state == 'stopped'">
      <div class="bg-green-600 inline-block text-white font-bold p-4 rounded-xl" @click="startTimer">Start Timer</div>
    </div>

    <div v-else-if="state == 'running'">
      Pause Timer
    </div>

    <div v-else-if="state == 'paused'">
      Continue Timer
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
      interval: false,
      intervalTime: false,
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
  },

  methods: {
    startTimer() {
      this.intervalTime = new Date();
      this.state = 'running';

      this.interval = setInterval(() => {
        // Find the difference between the current time and the time of the last interval
        // It should be 500 ms, but the execution time of setInterval is variable (if the browser window is in focus, etc)
        const currentTime = new Date();
        const difference = currentTime.getTime() - this.intervalTime.getTime();

        this.time += difference;
        this.intervalTime = currentTime;
      }, 500);
    },
  }
}
</script>
