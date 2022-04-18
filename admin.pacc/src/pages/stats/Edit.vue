<template>
  <template v-if="stat">
    <div class="bg-white rounded-10 p-3 mt-5">
      <div class="d-flex flex-column">
        <div class="d-flex align-items-center justify-content-between">
          <input
            v-model="stat.name"
            class="form-control form-control-lg p-0 border-0 mb-3"
            @change="saveName()"
            role="button"
          />
          <button class="btn btn-sm btn-primary lh-normal" @click="download">
            Скачать
          </button>
        </div>

        <div class="d-flex align-items-center justify-content-between">
          <go-back link="/stats" />
          <save-indicator :count="currentlySaving" :success="currentlySavingSuccess" />
        </div>
      </div>
    </div>

    <div class="bg-white rounded-10 p-3 mt-3">
      <div class="font-weight-bold">Параметры</div>
      <div
        v-for="(field, i) in stat.components.fields"
        :key="i"
        class="field rounded-10"
        :class="{ 'drag-commencing': !!fieldDrag.id }"
        :draggable="!!field.id"
        @dragstart="(e) => onDragStart(e, field.id)"
        @dragover.prevent="(e) => onDragOver(e, field.id)"
        @dragleave="(e) => onDragLeave(e)"
        @dragend="(e) => onDragEnd(e)"
      >
        <i class="fas fa-bars text-muted ms-2" />
        <select
          v-model="field.typeId"
          @change="(e) => saveField({ id: field.id, typeId: Number(e.target.value) })"
          class="bg-transparent form-control form-control-sm border-0 ms-3"
          role="button"
        >
          <option :value="null" :disabled="true">Выберите параметр</option>
          <option v-for="fieldType in fieldTypes" :key="fieldType.id" :value="fieldType.id">
            {{ fieldType.alias }}
          </option>
        </select>
        <button class="btn btn-danger btn-sm me-2" @click="deleteComponent(field.id)">
          <i class="far fa-trash-alt" />
        </button>
      </div>
      <hr class="w-100" />
      <a href="#" @click.prevent="saveField" class="link-primary p-2">Добавить параметр</a>
    </div>

    <div class="bg-white rounded-10 p-3 mt-3">
      <div class="font-weight-bold">Условия</div>
      <div
        v-for="condition in stat.components.conditions"
        :key="condition.id"
        class="d-flex align-items-center justify-content-between p-2"
      >
        <div class="d-flex align-items-center">
          <select
            v-model="condition.fieldTypeId"
            @change="(e) => saveCondition({ id: condition.id, fieldTypeId: Number(e.target.value) })"
            class="form-control form-control-sm w-fit-content"
          >
            <option :value="null">Выберите параметр</option>
            <option v-for="fieldType in fieldTypes" :key="fieldType.id" :value="fieldType.id">
              {{ fieldType.alias }}
            </option>
          </select>
          <select
            v-model="condition.typeId"
            @change="(e) => saveCondition({ id: condition.id, typeId: Number(e.target.value) })"
            class="form-control form-control-sm w-fit-content ms-1"
          >
            <option :value="null" :disabled="true">Выберите тип условия</option>
            <option v-for="conditionType in conditionTypes" :key="conditionType.id" :value="conditionType.id">
              {{ conditionType.name }}
            </option>
          </select>
          <div v-for="index in condition.inputCount" :key="index" class="ms-1">
            <input
              v-if="condition.inputType === 'number'"
              type="number"
              class="form-control form-control-sm"
              :value="condition.values[index - 1]"
              @change="(e) => handleConditionValueChange(condition, index, e.target.value)"
              placeholder="Введите значение"
              min="0"
            />
            <template v-else-if="condition.inputType === 'date'">
              <input
                v-if="newConditionHelper.dateAbsolute"
                type="date"
                class="form-control form-control-sm"
                :value="condition.values[index - 1]"
                @change="(e) => handleConditionValueChange(condition, index, e.target.value)"
                placeholder="Выберите дату"
              />
              <select
                v-else
                class="form-control form-control-sm"
                :value="condition.values[index - 1]"
                @change="(e) => handleConditionValueChange(condition, index, e.target.value)"
              >
                <option disabled selected :value="undefined">
                  Выберите период
                </option>
                <option value="-1 day">День назад</option>
                <option value="-3 days">3 дня назад</option>
                <option value="-1 week">Неделю назад</option>
                <option value="-2 weeks">2 недели назад</option>
                <option value="-1 month">1 месяц назад</option>
                <option value="-3 months">3 месяца назад</option>
                <option value="-6 months">6 месяцев назад</option>
                <option value="-1 year">Год назад</option>
              </select>
            </template>
            <input
              v-else-if="condition.inputType === 'text'"
              class="form-control form-control-sm"
              :value="condition.values[index - 1]"
              @change="(e) => handleConditionValueChange(condition, index, e.target.value)"
              placeholder="Введите значение"
            />
            <input
              v-else-if="condition.inputType === 'email'"
              class="form-control form-control-sm"
              :value="condition.values[index - 1]"
              @change="(e) => handleConditionValueChange(condition, index, e.target.value)"
              placeholder="89990001234"
              pattern="^8[0-9]{3}[0-9]{3}[0-9]{2}[0-9]{2}$"
            />
          </div>
          <custom-switch
            v-if="condition.typeId && condition.inputType === 'date'"
            v-model="newConditionHelper.dateAbsolute"
            label="Точная дата"
            :size="16"
            class="ms-1"
          />
        </div>
        <button class="btn btn-danger btn-sm" @click="deleteComponent(condition.id)">
          <i class="far fa-trash-alt" />
        </button>
      </div>
      <hr class="w-100" />
      <a href="#" @click.prevent="saveCondition" class="link-primary p-2">Добавить условие</a>
    </div>

    <div class="bg-white rounded-10 p-3 mt-3">
      <div class="font-weight-bold">Сортировка</div>
      <div v-if="stat.components.order.id" class="d-flex align-items-center">
        <select
          v-model="stat.components.order.fieldTypeId"
          @change="(e) => saveOrder({ id: stat.components.order.id, fieldTypeId: Number(e.target.value) })"
          class="form-control form-control-sm m-2 w-fit-content"
          role="button"
        >
          <option :value="null">Выберите параметр</option>
          <option v-for="fieldType in fieldTypes" :key="fieldType.id" :value="fieldType.id">
            {{ fieldType.alias }}
          </option>
        </select>
        <select
          v-model="stat.components.order.direction"
          @change="(e) => saveOrder({ id: stat.components.order.id, direction: Number(e.target.value) })"
          class="form-control form-control-sm m-2 w-fit-content"
          role="button"
        >
          <option :value="false">
            По возрастанию
          </option>
          <option :value="true">
            По убыванию
          </option>
        </select>
        <button class="btn btn-danger btn-sm ms-auto" @click="deleteComponent(stat.components.order.id)">
          <i class="far fa-trash-alt" />
        </button>
      </div>
      <a v-else href="#" @click.prevent="saveOrder()" class="link-primary p-2">Использовать сортировку</a>
    </div>
  </template>
</template>

<style lang="scss" scoped>
.field {
  display: flex;
  align-items: center;
  justify-content: space-between;
  cursor: grab;
  user-select: none;
  border: 1px solid transparent;
  &:hover {
    background: #eee;
  }
  &.drag-over-top {
    border-top: 1px solid #0d6efd;
    // border-top: 40px solid transparent;
  }
  &.drag-over-bottom {
    border-bottom: 1px solid #0d6efd;
    // border-bottom: 40px solid transparent;
  }
  &.drag-commencing {
    background: white;
  }
  transition: 0.125s all linear;
}
</style>

<script lang="ts">
import Api from "@/common/api";
import { Options, Vue } from "vue-class-component";
import Stat from "@/types/stats/Stat";
import StatConditionSaveForm from "@/types/stats/StatConditionSaveForm";
import StatFieldSaveForm from "@/types/stats/StatFieldSaveForm";
import StatOrderSaveForm from "@/types/stats/StatOrderSaveForm";
import CustomSwitch from "@/components/CustomSwitch.vue";
import GoBack from "@/components/GoBack.vue";
import SaveIndicator from "@/components/SaveIndicator.vue";

@Options({
  components: { CustomSwitch, GoBack, SaveIndicator },
})
export default class StatEdit extends Vue {
  stat: Stat | null = null;
  conditionTypes: StatConditionSaveForm[] = [];
  fieldTypes: StatFieldSaveForm[] = [];
  newConditionHelper = {
    dateAbsolute: false,
  };
  fieldDrag = {
    id: 0,
    placement: -1,
    overId: 0,
  };
  currentlySaving = 0;
  currentlySavingSuccess = false;

  get statId() {
    return Number(this.$route.params.statId);
  }

  get componentModelTypeIds() {
    return {
      field: 5,
      condition: 6,
      order: 8,
    };
  }

  mounted() {
    this.load();
  }

  load() {
    Api.stats.common().then((response) => {
      this.conditionTypes = response.data.conditionTypes;
      this.fieldTypes = response.data.fieldTypes;

      Api.stats.getOne(this.statId).then((response) => {
        if (response.success) {
          this.stat = response.data;
        } else {
          this.$notifications.error(response.error);
        }
      });
    });
  }

  saveCondition(form: StatConditionSaveForm = {}) {
    return this.saveComponent(this.componentModelTypeIds.condition, form);
  }

  saveField(form: StatFieldSaveForm = {}) {
    return this.saveComponent(this.componentModelTypeIds.field, form);
  }

  saveOrder(form: StatOrderSaveForm = {}) {
    return this.saveComponent(this.componentModelTypeIds.order, form);
  }

  saveComponent(modelTypeId: number, form: StatFieldSaveForm | StatConditionSaveForm | StatOrderSaveForm) {
    this.currentlySaving++;
    return Api.stats.component
      .save({
        statisticsId: this.statId,
        modelTypeId: modelTypeId,
        ...form,
      })
      .then((response) => {
        this.currentlySavingSuccess = response.success;
        !response.success && this.$notifications.error(response.error);
      })
      .finally(() => {
        this.currentlySaving--;
        this.load();
      });
  }

  deleteComponent(id: number) {
    this.currentlySaving++;
    Api.stats.component
      .delete(id)
      .then((response) => {
        this.currentlySavingSuccess = response.success;
        !response.success && this.$notifications.error(response.error);
      })
      .finally(() => {
        this.load();
        this.currentlySaving--;
      });
  }

  onDragStart(e: DragEvent, id: number) {
    this.fieldDrag.id = id;

    const target = e.target as HTMLElement;

    const copy = target.cloneNode(true) as HTMLElement;
    copy.style.opacity = "0.5";
    copy.style.transform = "translateX(-10000px)";
    document.body.appendChild(copy);
    e.dataTransfer?.setDragImage(copy, 0, 0);
  }

  onDragOver(e: DragEvent, id: number) {
    // calculate where mouse is relative to the dragged over element
    const blockContainer = (e.target as HTMLElement).closest(".field")!;
    const clientRect = blockContainer.getBoundingClientRect();
    const percentage = (e.clientY - clientRect.top) / clientRect.height;
    const placement = Math.sign(percentage - 0.5);

    if (this.fieldDrag.overId === id && this.fieldDrag.placement === placement) {
      return;
    }

    this.onDragLeave(e);
    blockContainer.classList.add(placement === -1 ? "drag-over-top" : "drag-over-bottom");

    this.fieldDrag.placement = placement;
    this.fieldDrag.overId = id;
  }

  onDragLeave(e: DragEvent) {
    const blockContainer = (e.target as HTMLElement).closest(".field")!;
    blockContainer.classList.remove("drag-over-top");
    blockContainer.classList.remove("drag-over-bottom");
  }

  onDragEnd(e: DragEvent) {
    const dragged = this.stat?.components.fields.find((f) => f.id === this.fieldDrag.id);
    const draggedOver = this.stat?.components.fields.find((f) => f.id === this.fieldDrag.overId);

    this.onDragLeave(e);
    this.resetDrag();

    if (!dragged || !draggedOver || dragged.id === draggedOver.id) {
      return;
    }

    const weightDiff = 5;
    const newWeight = draggedOver.weight + this.fieldDrag.placement * weightDiff;

    this.saveField({
      id: dragged.id,
      weight: newWeight,
    });
  }

  resetDrag() {
    this.fieldDrag = {
      id: 0,
      overId: 0,
      placement: -1,
    };
  }

  handleConditionValueChange(condition: StatConditionSaveForm, valueIndex: number, value: string) {
    if (!condition.values) {
      condition.values = [];
    }
    condition.values[valueIndex - 1] = value;
    this.saveCondition(condition);
  }

  saveName() {
    this.currentlySaving++;
    Api.stats
      .save({
        id: this.statId,
        name: this.stat!.name,
      })
      .then((response) => {
        this.currentlySavingSuccess = response.success;
        if (!response.success) {
          this.$notifications.error(response.error);
          this.load();
        }
      })
      .finally(() => {
        this.currentlySaving--;
      });
  }

  download() {
    Api.stats.download(this.statId).then((response) => {
      if (!response.success) {
        return this.$notifications.error(response.error);
      }

      const linkSource =
        "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," + response.data.bytes;

      const downloadLink = document.createElement("a");
      downloadLink.href = linkSource;
      downloadLink.download = `Статистика ${this.stat?.name}`;
      downloadLink.target = "_blank";

      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
    });
  }
}
</script>
