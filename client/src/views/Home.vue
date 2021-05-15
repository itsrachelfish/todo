<template>
  <div class="home">
    <div class="text-xl mb-4">
      {{ timerOutput }}
    </div>

    <div v-if="state == 'stopped'">
      <div class="bg-green-600 cursor-pointer inline-block text-white font-bold m-4 p-4 rounded-xl" @click="startTimer">Start Timer</div>
    </div>

    <div v-else-if="state == 'running'">
      <div class="bg-yellow-300 cursor-pointer inline-block font-bold m-4 p-4 rounded-xl" @click="pauseTimer">Pause Timer</div>
    </div>

    <div v-else-if="state == 'paused'">
      <div class="bg-green-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="startTimer">Continue Recording</div>
      <div class="bg-blue-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="saveHistory">Save Time</div>
      <div class="bg-red-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="clearTimer">Clear Timer</div>
    </div>

    <h1 class="text-2xl mt-4 mb-1 font-bold">History</h1> Only show today? <input type="checkbox">
    <hr>

    <div v-for="(row, index) in history" :key="row.date">
      <div class="grid grid-cols-6 mt-2" :class="(index % 2) ? 'bg-gray-200' : ''">
        <div>
          {{ row.date.toLocaleString() }}
        </div>

        <div>
          {{ displayTimer(row.duration) }}
        </div>

        <div class="col-span-4">
          {{ row.description }}
        </div>
      </div>
    </div>

    <hr>
    <div class="bg-pink-500 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="manualHistory">Add History Manually</div>
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
      history: [{
        date: new Date(),
        duration: 31337,
        description: 'this is an example',
      },
      {
        date: new Date(),
        duration: 1313337,
        description: 'this is a very cool example!!!! wow it\'s awesome',
      },
      {
        date: new Date(),
        duration: 313337,
        description: 'this is an example',
      }],
    }
  },

  computed: {
    timerOutput() {
      return this.displayTimer(this.time);
    },
  },

  methods: {
    displayTimer(timeRemaining) {
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

    startTimer() {
      this.state = 'running';
      this.intervalTime = new Date();

      this.interval = setInterval(() => {
        // Find the difference between the current time and the time of the last interval
        // It should be 500 ms, but the execution time of setInterval is variable (if the browser window is in focus, etc)
        const currentTime = new Date();
        const difference = currentTime.getTime() - this.intervalTime.getTime();

        this.time += difference;
        this.intervalTime = currentTime;
      }, 500);
    },

    pauseTimer() {
      this.state = 'paused';
      clearInterval(this.interval);

      const currentTime = new Date();
      const difference = currentTime.getTime() - this.intervalTime.getTime();

      this.time += difference;
    },

    clearTimer() {
      this.state = 'stopped';
      clearInterval(this.interval);

      this.time = 0;
    },
  }
}
</script>
