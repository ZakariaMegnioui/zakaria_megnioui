import { defineStore } from "pinia";
import { ref, reactive } from "vue";
import { ElNotification, ElMessageBox, ElMessage } from "element-plus";
import { useI18n } from "vue-i18n";
import ApiService from "../generale/ApiService";

export const useProductStore = defineStore("product", {
  state: () => {
    const { t } = useI18n({ useScope: "global" });
    const tableData = ref<Product[]>([]);
    const cardData = ref<Card>();
    const actions = {
      slot: true,
      label: t("actions"),
      fixed: "right",
      width: "120px",
      expand: false,
      item_actions: [
        {
          label: t('edit'),
          name: 'edit',
          icon: 'bi bi-pencil-square',
          permissions: ['edit product']
        },
        {
          label: t('delete'),
          name: 'delete',
          icon: 'bi bi-trash-fill',
          permissions: ['delete product']
        }
      ]
    };

    const pageSizes = [5, 10, 15, 20, 25];
    const selectedItems = ref([]);

    const meta = ref<PagiMeta>({
      current_page: 1,
      from: 1,
      last_page: 1,
      links: [],
      path: "",
      per_page: 10,
      to: 1,
      total: 0,
    });

    const params = reactive({
      page: 1,
      per_page: 10,
      search: "",
      category:'',
      sort_by_name:'',
      sort_by_price:''

    });

    const detailsColumn = [
      {
        prop: "name",
        label: t("name"),
        sortable: false,
      },
      {
        prop: "description",
        label: t("description"),
        sortable: false,
      },
      {
        prop: "price",
        label: t("price"),
        sortable: false,
      },
    ];

    const loading = ref(false);
    const showDetailsModal = ref(false);
    const showAddProductModal = ref(false);
    const showEditProductModal = ref(false);
    const selectedItemIds = ref([]);
    const currentProduct = ref<Product>();

    return {
      t,
      tableData,
      selectedItems,
      selectedItemIds,
      cardData,
      meta,
      currentProduct,
      params,
      pageSizes,
      loading,
      actions,
      showDetailsModal,
      showAddProductModal,
      showEditProductModal,
      detailsColumn,
    };
  },

  actions: {
    setParams(key, value) {
      if (key !== "per_page" && key !== "page") {
        this.params.page = 1;
      }
      this.params[key] = value;
      this.fetchData(this.params);
    },

    handleSelectionChange(val) {
      this.selectedItems = val;
    },

    openAction(name, item) {
      switch (name) {
        case 'edit':
          this.currentProduct = item;
          this.showEditProductModal = true;
          break;
        case 'delete':
          this.deleteProduct(item);
          break;
        default:
          break;
      }
    },

    async handleFilter() {
      this.fetchData();
      this.getCardData();
    },

    async fetchData() {
      this.loading = true;
      let res = await ApiService.get("products", this.params);
      res = res.data;
      this.tableData = res.data;
      this.meta = res.meta;
      this.loading = false;
    },

    async getCardData() {
      let res = await ApiService.get("/products/stats");
      this.cardData = res.data;
    },

    async updateProduct(product) {
      try {
        const res = await ApiService.put(`products/${product.id}`, product);
        ElNotification({
          type: "success",
          message: this.t("product_edited_successfuly"),
        });
        this.handleFilter();
        return res;
      } catch (error) {
        ElNotification({
          type: "error",
          message: "Error",
        });
      }
    },

    async addProduct(newProduct) {
      try {
        const res = await ApiService.post("products", newProduct);
        ElNotification({
          type: "success",
          message: this.t("product_added_successfully"),
        });
        this.handleFilter();
        return res;
      } catch (error) {
        ElNotification({
          type: "error",
          message: this.t("error_adding_product"),
        });
        throw error;
      }
    },

    async deleteProduct(data) {
      ElMessageBox.confirm(
        this.t("delete_product") + data.name,
        this.t("warning"),
        {
          confirmButtonText: this.t("ok"),
          cancelButtonText: this.t("cancel"),
          type: "warning",
        }
      )
        .then(() => {
          ApiService.delete(`products/${data.id}`)
            .then(() => {
              ElMessage({
                type: "success",
                message: this.t("delete_completed"),
              });
              this.handleFilter();
            })
            .catch((error) => {
              ElNotification({
                type: "error",
                message: this.t("error"),
              });
            });
        })
        .catch(() => {
          ElMessage({
            type: "info",
            message: this.t("delete_canceled"),
          });
        });
    },
  },
});

interface Product {
  id: number;
  name: string;
  description: string;
  price: number;
}

interface Card {
  title: string;
  color: string;
  icon: string;
  stats: number;
}

export interface PagiMeta {
  current_page: number;
  from: number;
  last_page: number;
  links: {
    url: string;
    label: string;
    active: boolean;
  }[];
  path: string;
  per_page: number;
  to: number;
  total: number;
}
