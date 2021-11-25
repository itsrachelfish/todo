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
              {{ displayTimer(1234567) }}
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
  </div>
</template>

<script>
export default {
  name: 'Activities',

  data() {
    return {
      activities: [],
      interval: false,
      intervalTime: false,
    }
  },

  methods: {
    addActivity() {
      const name = prompt("What's the activity?");
      const activity = {
        name,
        time: 0,
        state: 'stopped',
      }

      this.activities.push(activity);
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
  },
}
</script>
