<template>
  <div class="todo">
    <h1 class="text-2xl font-bold">Important Stuff Todo</h1>

    <div class="inline-block text-left mb-8">
      <ol class="list-decimal">
        <li><s>Delete button for history rows</s></li>
        <li><s>Show new history first</s></li>
        <li><s>Write current time to localstorage whenever paused or something</s></li>
        <li>Filter history by date</li>
        <li>Started: <u>Categorizing history into groups / tagging</u></li>
        <li>Display total amount of time in the history</li>
        <li>Button for saving data to a file and importing</li>
      </ol>
    </div>

    <h1 class="text-2xl font-bold">Medium term tasks</h1>

    <div class="inline-block text-left mb-8">
      <ol class="list-decimal">
        <li><s>Keep a timer running between refreshing / page navigation</s> (Ended up being really simple...)</li>
        <li><s>Starting a new timer when one is already paused</s></li>
        <li><s>Ability to edit the time of a paused timer</s></li>
        <li><b>IMPORTANT!!! --></b> Track the start and end time of each history entry</li>
        <li>(Deprecated?) Add descriptions while a timer is runnning</li>
        <li>Use modals instead of alert / confirm windows</li>
        <li>Arbitrary events saved in the history (not time logs)</li>
      </ol>
    </div>

    <h1 class="text-2xl font-bold">Long term planning</h1>

    <div class="inline-block text-left">
      <ol class="list-decimal">
        <li>Actually save history to a database</li>
        <li>Sync timers across devices</li>
      </ol>
    </div>
  </div>
</template>
