<template>
  <!-- layout: table -->
  <k-table
    v-if="layout === 'table'"
    :columns="columns"
    :index="
      items.filter((row) => row.image && Object.keys(row.image).length > 0)
        .length === 0
        ? false
        : null
    "
    :rows="items"
    :sortable="sortable"
    class="k-items"
    @option="$emit('option', ...$event)"
  >
    <template #index="{ row }">
      <k-item-image
        v-if="row.image && Object.keys(row.image).length > 0"
        :image="row.image"
        layout="list"
        :width="row.width"
        class="k-table-index"
      />
    </template>
    <template #options="{ row, rowIndex, options }">
      <k-status-icon v-if="row.flag" v-bind="row.flag" />
      <k-options-dropdown
        :options="row.options || options"
        @option="onOption($event, row, rowIndex)"
      />
    </template>
  </k-table>

  <!-- other layouts -->
  <k-draggable
    v-else
    class="k-items"
    :class="'k-' + layout + '-items'"
    :handle="true"
    :options="dragOptions"
    :data-layout="layout"
    :data-size="size"
    :list="items"
    @change="$emit('change', $event)"
    @end="$emit('sort', items, $event)"
  >
    <slot>
      <k-item
        v-for="(item, itemIndex) in items"
        :key="item.id || itemIndex"
        v-bind="item"
        :class="{ 'k-draggable-item': sortable && item.sortable }"
        :image="imageOptions(item)"
        :layout="layout"
        :link="link ? item.link : false"
        :sortable="sortable && item.sortable"
        :width="item.column"
        @click="$emit('item', item, itemIndex)"
        @drag="onDragStart($event, item.dragText)"
        @flag="$emit('flag', item, itemIndex)"
        @mouseover.native="$emit('hover', $event, item, itemIndex)"
        @option="$emit('option', $event, item, itemIndex)"
      >
        <template #options>
          <slot name="options" :item="item" :index="itemIndex" />
        </template>
      </k-item>
    </slot>
  </k-draggable>
</template>

<script>
export default {
  inheritAttrs: false,
  props: {
    columns: Object,
    items: {
      type: Array,
      default() {
        return [];
      }
    },
    layout: {
      type: String,
      default: "list"
    },
    link: {
      type: Boolean,
      default: true
    },
    image: {
      type: [Object, Boolean],
      default() {
        return {};
      }
    },
    sortable: Boolean,
    /**
     * Placeholder text and icon for empty state
     */
    empty: {
      type: [String, Object]
    },
    /**
     * Card sizes.
     */
    size: {
      type: String,
      default: "default"
    }
  },
  computed: {
    dragOptions() {
      return {
        sort: this.sortable,
        disabled: this.sortable === false,
        draggable: ".k-draggable-item"
      };
    }
  },
  methods: {
    onDragStart($event, dragText) {
      this.$store.dispatch("drag", {
        type: "text",
        data: dragText
      });
    },
    imageOptions(item) {
      let globalOptions = this.image;
      let localOptions = item.image;

      if (globalOptions === false || localOptions === false) {
        return false;
      }

      if (typeof globalOptions !== "object") {
        globalOptions = {};
      }

      if (typeof localOptions !== "object") {
        localOptions = {};
      }

      return {
        ...localOptions,
        ...globalOptions
      };
    }
  }
};
</script>

<style>
/**
 * Cards
 */
.k-cards-items {
  --min: 13rem;
  --max: 1fr;
  --gap: 1.5rem;
  --column-gap: var(--gap);
  --row-gap: var(--gap);
  display: grid;
  grid-column-gap: var(--column-gap);
  grid-row-gap: var(--row-gap);
  grid-template-columns: repeat(auto-fill, minmax(var(--min), var(--max)));
}

@media screen and (min-width: 30em) {
  .k-cards-items[data-size="tiny"] {
    --min: 10rem;
  }
  .k-cards-items[data-size="small"] {
    --min: 16rem;
  }
  .k-cards-items[data-size="medium"] {
    --min: 24rem;
  }
  .k-cards-items[data-size="large"],
  .k-cards-items[data-size="huge"] {
    --min: 1fr;
  }

  .k-column[data-width="1/4"] .k-cards-items,
  .k-column[data-width="1/5"] .k-cards-items,
  .k-column[data-width="1/6"] .k-cards-items {
    --min: 1fr;
  }
}

@media screen and (min-width: 65em) {
  .k-cards-items[data-size="large"] {
    --min: 32rem;
  }
}

/**
 * Cardlets
 */
.k-cardlets-items {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(16rem, 1fr));
  grid-gap: 0.5rem;
}

/**
 * List
 */
.k-list-items .k-list-item:not(:last-child) {
  margin-bottom: 2px;
}

/**
 * Table
 */
.k-table.k-items th.k-table-options-column,
.k-table.k-items td.k-table-options-column {
  display: flex;
  width: calc(2 * var(--table-row-height));
}
.k-table.k-items .k-status-icon {
  height: var(--table-row-height);
  width: var(--table-row-height);
}
.k-table.k-items .k-item-figure + .k-table-sort-handle {
  background: var(--color-gray-100);
}
</style>
