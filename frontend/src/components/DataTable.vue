<script setup >
import { useI18n } from "vue-i18n";
import { defineEmits, reactive, ref, toRefs } from "vue"
import { ArrowDown } from '@element-plus/icons-vue'
import { ChatDotRound, More } from '@element-plus/icons-vue'


const props = defineProps({
  selection: {
    type: Boolean,
    default: true
  },
  loading: {
    type: Boolean,
    default: false
  },
  stripe: {
    type: Boolean,
    default: false
  },

  border: {
    type: Boolean,
    default: true
  },

  tooltipEffect: {
    type: String,
    default: 'dark'
  },
  tableData: {
    type: Array,
    required: true
  },
  tableColumn: {
    type: Array,
    required: true
  },
  tableOption: {
    type: Object,
    default: () => {
      return {}
    }
  },
  itemActions: {
    type: Array,
    default() {
      return [
        {
          name: 'edit-item',
          icon: 'fas fa-pencil-alt',
          class: 'btn btn-info'
        },
        {
          name: 'delete-item',
          icon: 'fas fa-trash-alt',
          class: 'btn btn-danger'
        }
      ]
    }
  },
  paginate: {
    type: Boolean,
    default: true
  },
  layout: {
    type: String,
    default: 'total, sizes, prev, pager, next, jumper'
  },
  pageSizes: {
    type: Array,
    default: () => [10, 30, 50, 100]
  },
  pagination: {
    type: Object,
    default: () => {
      return {
        total: 0,
        currentPage: 1,
        pageSize: 10
      }
    },
  },
  tableHeight: {
    type: String,
  }
});


const { t } = useI18n()
const resData = reactive({
  tableRef: ref(null)
})
const emit = defineEmits(["close-tag", "handle-sort-change", "handleSelectionChange", "set-params", "table-action"]);

const show = (row, key) => {
  let arr = key.split('.')
  for (let i in arr) {
    row = row[arr[i]]
  }
  return row
}
const handleSortChange = (sort) => {
  emit('handle-sort-change', sort)
}
const handleSelectionChange = (val) => {
  emit('handleSelectionChange', val)
}
const filterHandler = (value, row, column) => {
  const property = column['property']
  return row[property] === value
}

const toggleExpand = (row) => {
  resData.tableRef.toggleRowExpansion(row)
}
const handleSizeChange = (perPage) => {
  emit('set-params', 'per_page', perPage)
}
const handleCurrentChange = (current) => {
  emit('set-params', 'page', current)
}
const callAction = (action, data) => {
  emit('table-action', action, data)
}
const closeTag = (obj) => {
  emit('close-tag', obj)
}
const handleToggleRowSelection = (row, isSelected) => {
  nextTick(() => {
    resData.tableRef.toggleRowSelection(row, isSelected)
  })
}
const getRowKey = (row) => {
  return row.id
}



</script>
<template>
  <section>
    <el-table ref="tableRef" v-loading="loading"
      :style="'width: 100%; height: ' + (tableHeight === '' ? '100%' : tableHeight)" highlight-current-row
      :tooltip-effect="tooltipEffect" :row-key="getRowKey" :stripe="stripe" :fit="true" :data="tableData"
      :height="tableHeight" :border="border" @sort-change="handleSortChange" @selection-change="handleSelectionChange"
      :header-cell-style="{}" :cell-style="{}">

      <el-table-column v-if="selection" type="selection" width="50" @row-click="toggleExpand" />

      <el-table-column v-if="tableOption.expand" type="expand">
        <template #default="props">
          <div>
            <p m="t-0 b-2">State: {{ props.row.state }}</p>
            <p m="t-0 b-2">City: {{ props.row.city }}</p>
            <p m="t-0 b-2">Address: {{ props.row.address }}</p>
            <p m="t-0 b-2">Zip: {{ props.row.zip }}</p>
            <h3>Family</h3>
            <el-table :data="props.row.family" :border="childBorder">
              <el-table-column label="Name" prop="name" />
              <el-table-column label="State" prop="state" />
              <el-table-column label="City" prop="city" />
              <el-table-column label="Address" prop="address" />
              <el-table-column label="Zip" prop="zip" />
            </el-table>
          </div>
        </template>
      </el-table-column>

      <template v-for="(item, index) in tableColumn" :key="index">

        <el-table-column v-if="item.filters" :key="index + 1" :width="item.width" :align="item.align" :label="item.label"
          :prop="item.prop" :sortable="item.sortable" :filters="item.filters ? item.filters : []"
          :filter-method="filterHandler" :show-overflow-tooltip="!item.operate">
          <template #default="scope">
            <slot v-if="item.slot" :name="item.prop" v-bind="scope" />
            <span v-else>{{ show(scope.row, item.prop, scope) }}</span>
          </template>

        </el-table-column>

        <el-table-column v-else-if="item.type === 'selection'" :type="item.type" :width="item.width" :key="index + 1">
        </el-table-column>
        <el-table-column v-else :key="index + 1" :width="item.width" :align="item.align ? item.align : 'center'"
          :label="item.label" :prop="item.prop" :sortable="item.sortable" show-overflow-tooltip>
          <template #default="scope">
            <slot v-if="item.slot" :name="item.prop" v-bind="scope" />
            <span v-else>{{ show(scope.row, item.prop) }}</span>
          </template>
        </el-table-column>
      </template>
      <el-table-column v-if="tableOption.label" :fixed="tableOption.fixed"
        :align="tableOption.align ? tableOption.align : 'center'" :width="tableOption.width" :label="tableOption.label">
        <template #default="scope">
          <template v-if="tableOption.item_actions">
            <el-dropdown>
              <span class="el-dropdown-link">
                <el-icon>
                  <More class="more" />
                </el-icon>
              </span>
              <template #dropdown>
                <el-dropdown-menu>
                  <div v-for="(action, index) in tableOption.item_actions" :key="index">
                    <el-dropdown-item @click="callAction(action.name, scope.row)">
                      <i :class="action.icon" class="pr-1"></i>
                      {{ action.label }}
                    </el-dropdown-item>
                  </div>
                </el-dropdown-menu>
              </template>
            </el-dropdown>
          </template>
        </template>
      </el-table-column>
    </el-table>
  </section>

  <template v-if="paginate && pagination.total > 0">
    <section class="d-flex justify-content-center my-5">
      <el-pagination class="overflow-auto mb-5" background :page-sizes="pageSizes"
        v-model:current-page="pagination.current_page" :page-size="pagination.per_page" :total="pagination.total"
        @size-change="handleSizeChange" @current-change="handleCurrentChange" layout="prev, pager, next, jumper" />
    </section>
  </template>
</template>

<style scoped>
.more {
  transform: rotate(90deg);
}
</style>
