<template>
  <div class="bg-white rounded-10 p-3 mt-5">
    <h3 class="m-0">Календарь</h3>
  </div>

  <div class="d-flex mt-3 mb-5 mx-0">
    <date-picker @select="onDateSelected" />
    <day-schedule v-if="selectedDate" :date="selectedDate" />
  </div>
</template>

<script lang="ts">
import DatePicker from "@/components/calendar/DatePicker.vue";
import DaySchedule from "@/components/calendar/DaySchedule.vue";
import { Options, Vue } from "vue-class-component";

@Options({
  components: { DatePicker, DaySchedule },
})
export default class CalendarIndex extends Vue {
  selectedDate: string = "";

  onDateSelected(value: string) {
    this.selectedDate = value;
  }
}
</script>
