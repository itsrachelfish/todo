<template>
  <div class="home">
    <div class="text-5xl font-bold mb-4">
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
      <div class="bg-yellow-600 cursor-pointer inline-block text-white font-bold m-4 p-4 rounded-xl" @click="editTimer">Edit Time</div>
      <div class="bg-blue-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="saveHistory">Save Time</div>
      <div class="bg-red-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="clearTimer">Clear Timer</div>
    </div>

    <h1 class="text-2xl mt-4 mb-1 font-bold">History</h1> Only show today? <input type="checkbox">
    <hr>

    <div v-for="(row, index) in [...history].reverse()" :key="row.date">
      <div class="grid grid-cols-7 mt-2" :class="(index % 2) ? 'bg-gray-200' : ''">
        <div>
          {{ row.date.toLocaleString() }}
        </div>

        <div>
          {{ displayTimer(row.duration) }}
        </div>

        <div class="col-span-4">
          <span class="align-middle leading-none">{{ row.description }}</span>
        </div>

        <div>
          <div class="bg-gray-400 hover:bg-red-600 leading-none align-middle cursor-pointer inline-block text-white font-bold pt-1 px-4 rounded-xl" @click="deleteHistory(history.length - (index + 1))">Delete</div>
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
      history: [],
    }
  },

  computed: {
    timerOutput() {
      return this.displayTimer(this.time);
    },
  },

  created() {
    const savedHistory = localStorage.getItem('todoHistory');

    if(savedHistory) {
      this.history = JSON.parse(savedHistory).map((row) => {
        row.date = new Date(row.date);
        return row;
      });
    }
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

    editTimer() {
      const hours = parseInt(prompt('How many hours have you worked so far?'));
      const minutes = parseInt(prompt('How many minutes have you worked so far?'));

      this.time = (hours * 60 * 60 * 1000) + (minutes * 60 * 1000);
    },

    saveHistory() {
      const description = prompt('What were you working on?');

      if(description.length) {
        this.history.push({
          date: new Date(),
          duration: this.time,
          description: description,
        });

        localStorage.setItem('todoHistory', JSON.stringify(this.history));
        this.clearTimer();
      } else {
        alert('You have to enter a description!');
      }
    },

    manualHistory() {
      const hours = parseInt(prompt('How many hours did you work for?'));
      const minutes = parseInt(prompt('How many minutes did you work for?'));
      const description = prompt('What were you working on?');

      this.history.push({
        date: new Date(),
        duration: (hours * 60 * 60 * 1000) + (minutes * 60 * 1000),
        description: description,
      });

      localStorage.setItem('todoHistory', JSON.stringify(this.history));
    },

    deleteHistory(index) {
      const deleteConfirmed = confirm('Are you sure you want to delete this history?');

      if(deleteConfirmed) {
        this.history.splice(index, 1);
        localStorage.setItem('todoHistory', JSON.stringify(this.history));
      }
    },
  }
}
</script>
