<template>
  <div class="todo">
    <h1 class="text-2xl font-bold">Activities</h1>

    <div class="inline-block text-left mb-8">
      <template v-for="(activity, index) in activities" :key="index">
        <div class="border-4 border-blue-100 rounded-xl flex m-4 p-4">
          <div>
            <h2 class="text-xl font-bold">
              {{ activity.name }}
            </h2>

            <div class="text-5xl font-bold mb-4">
              {{ displayTimer(activity.time) }}
            </div>
          </div>

          <div v-if="activity.state == 'stopped'">
            <div class="bg-green-600 cursor-pointer inline-block text-white font-bold m-4 p-4 rounded-xl" @click="startTimer(index)">Start Timer</div>
          </div>

          <div v-else-if="activity.state == 'running'">
            <div class="bg-yellow-300 cursor-pointer inline-block font-bold m-4 p-4 rounded-xl" @click="pauseTimer(index)">Pause Timer</div>
          </div>

          <div v-else-if="activity.state == 'paused'">
            <div class="bg-green-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="startTimer(index)">Continue Recording</div>
            <div class="bg-yellow-600 cursor-pointer inline-block text-white font-bold m-4 p-4 rounded-xl" @click="editTimer(index)">Edit Time</div>
            <div class="bg-blue-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="saveHistory(index)">Save Time</div>
            <div class="bg-red-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="clearTimer(index)">Clear Timer</div>
          </div>

          <div>
            <span class="bg-gray-400 hover:bg-red-600 leading-none align-middle cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="deleteActivity(index)">Delete</span>
          </div>
        </div>
      </template>
    </div>

    <div>
      <div class="bg-blue-600 cursor-pointer inline-block text-white font-bold p-4 m-4 rounded-xl" @click="addActivity">Add An Activity</div>
    </div>

    <hr class="border-1 border-gray-200 my-8" />

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
  </div>
</template>

<script>
export default {
  name: 'Activities',

  data() {
    return {
      activities: [],
      history: [],
      interval: false,
      intervalTime: false,
    }
  },

  created() {
    this.restoreState();
    this.startIntervalWhenNecessary();
  },

  methods: {
    persistState() {
      localStorage.setItem('todoActivities', JSON.stringify(this.activities));
      localStorage.setItem('todoHistory', JSON.stringify(this.history));
    },

    restoreState() {
      const savedActivities = localStorage.getItem('todoActivities');
      const savedHistory = localStorage.getItem('todoHistory');

      if(savedActivities) {
        const parsedActivities = JSON.parse(savedActivities);

        if(Array.isArray(parsedActivities)) {
          this.activities = parsedActivities;
        }
      }

      if(savedHistory) {
        const parsedHistory = JSON.parse(savedHistory);

        if(Array.isArray(parsedHistory)) {
          this.history = parsedHistory.map((row) => {
            row.date = new Date(row.date);
            return row;
          });
        }
      }

    },

    addActivity() {
      const name = prompt("What's the activity?");
      const activity = {
        name,
        time: 0,
        state: 'stopped',
      }

      this.activities.push(activity);
      this.persistState();
    },

    deleteActivity(index) {
      const deleteConfirmed = confirm('Are you sure you want to delete this activity?');

      if(deleteConfirmed) {
        this.activities.splice(index, 1);
        this.persistState();
      }
    },

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

    intervalCallback() {
      // Find the difference between the current time and the time of the last interval
      // It should be 500 ms, but the execution time of setInterval is variable (if the browser window is in focus, etc)
      const currentTime = new Date();
      const difference = currentTime.getTime() - this.intervalTime.getTime();

      this.activities.forEach((activity, intervalIndex) => {
        if(activity.state == 'running') {
          this.activities[intervalIndex].time += difference;
        }
      });

      this.intervalTime = currentTime;

      // TODO: Is there a performance impact from doing this every tick? 
      this.persistState();
    },

    // Helper function to start the interval when there are timers currently active
    startIntervalWhenNecessary() {
      // Are any timers running?
      const runningTimers = this.activities.filter((activity) => {
        if(activity.state == 'running') {
          return true;
        }

        return false;
      });

      // If there are running timers, start the interval
      if(runningTimers.length) {
        this.intervalTime = new Date();
        this.interval = setInterval(this.intervalCallback, 500);
      }
    },


    // Helper function to clear the interval if there are no timers currently active
    stopIntervalWhenNecessary() {
      // Are any timers running?
      const runningTimers = this.activities.filter((activity) => {
        if(activity.state == 'running') {
          return true;
        }

        return false;
      });

      // If there are no running timers, clear the interval
      if(!runningTimers.length) {
        clearInterval(this.interval);
        this.interval = false;
      }
    },

    startTimer(index) {
      this.activities[index].state = 'running';

      if(!this.interval) {
        this.intervalTime = new Date();
        this.interval = setInterval(this.intervalCallback, 500);
      }

      this.persistState();
    },

    pauseTimer(index) {
      this.activities[index].state = 'paused';

      const currentTime = new Date();
      const difference = currentTime.getTime() - this.intervalTime.getTime();

      this.activities[index].time += difference;
      this.stopIntervalWhenNecessary();
      this.persistState();
    },

    clearTimer(index) {
      this.activities[index].state = 'stopped';
      this.activities[index].time = 0;
      this.stopIntervalWhenNecessary();
      this.persistState();
    },

    editTimer(index) {
      const hours = parseInt(prompt('How many hours have you worked so far?'));
      const minutes = parseInt(prompt('How many minutes have you worked so far?'));

      this.activities[index].time = (hours * 60 * 60 * 1000) + (minutes * 60 * 1000);
    },

    saveHistory(index) {
      const task = prompt('(Optional) What were you working on?');
      const description = task ? `${this.activities[index].name} - ${task}` :  this.activities[index].name;

      this.history.push({
        date: new Date(),
        duration: this.activities[index].time,
        description: description,
      });

      this.clearTimer(index);
      this.persistState();
    },

    deleteHistory(index) {
      const deleteConfirmed = confirm('Are you sure you want to delete this history?');

      if(deleteConfirmed) {
        this.history.splice(index, 1);
        localStorage.setItem('todoHistory', JSON.stringify(this.history));
      }
    },
  },
}
</script>
