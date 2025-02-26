<template>
  <div class="container">
    <LoadingVue v-if="isLoading" />
    
    <el-button @click="downloadExcel" type="primary" plain class="mb-1"
        >Download Excel
      </el-button>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-3 mb-3">
      <el-select
        class="listening"
        v-model="selectFilter"
        placeholder="Listening"
        style="width: 100%"
      >
        <el-option
          v-for="item in filters"
          :key="item.id"
          :label="item.name"
          :value="item.id"
        >
        </el-option>
      </el-select>
    </div>
    <el-table
      :data="tableData"
      style="max-width: 97%"
    >
      <el-table-column fixed label="Name" prop="name"> </el-table-column>
      <el-table-column label="Email" prop="email"> </el-table-column>
      <el-table-column label="Phone" prop="phone"> </el-table-column>
      <el-table-column align="right" width="300">
        <template slot="header" slot-scope="scope">
          <el-input
            v-model="search"
            size="small"
            placeholder="Type to search"
          />
        </template>
        <template slot-scope="scope">
          <el-dropdown v-if="scope.row.is_admin == 0" size="small" split-button type="info" class="mr-2">
            History
            <el-dropdown-menu  slot="dropdown">
              <el-dropdown-item
                ><span @click="handleHistoryLearn(scope.$index, scope.row)"
                  >Learn History</span
                ></el-dropdown-item
              >
              <el-dropdown-item
                ><span @click="handleHistory(scope.$index, scope.row)"
                  >Test History</span
                ></el-dropdown-item
              >
            </el-dropdown-menu>
          </el-dropdown>
          <el-button
            size="mini"
            type="warning"
            plain
            @click="handleEdit(scope.$index, scope.row)"
            v-if="isAdmin"
            >Edit
          </el-button>
          <el-button
            size="mini"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)"
            v-if="isAdmin"
            :disabled="scope.row.is_admin == 1"
            >Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>
    <div class="mt-2 flex items-center justify-center"> 
          <el-pagination background layout="prev, pager, next" v-model="page" :page-size="10" :total="total" @current-change="handleChangePage" />
        </div>
  </div>
</template>

<script>
import baseRequest from "../../../utils/baseRequest";
import LoadingVue from "../loading/Loading.vue";

export default {
  components: {
    LoadingVue,
  },
  data() {
    return {
      tableData: [
        {
          id: null,
          email: null,
          name: null,
          phone: null,
        },
      ],
      search: "",
      show: true,
      isLoading: false,
      isAdmin: true,
      isAdmin: true,
      total : 0,
      page: 1,
      timeOut: null,
      filters: [
        {
          id: 1,
          name: 'All'
        },
        {
          id: 2,
          name: 'Study'
        },
        {
          id: 3,
          name: 'Not study'
        },
      ],
      selectFilter: 1
    };
  },
  methods: {
    handleEdit(index, row) {
      window.location.href = `${$Api.baseUrl}/admin/user/edit/` + row.id;
    },
    handleHistory(index, row) {
      window.open(`${$Api.baseUrl}/admin/user/exam-history/` + row.id );
    },
    handleHistoryLearn(index, row) {
      window.open(`${$Api.baseUrl}/admin/user/learn-history/` + row.id);
    },
    handleDelete(index, row) {
      this.$confirm(
        "This will permanently delete the file. Continue?",
        "Warning",
        {
          confirmButtonText: "OK",
          cancelButtonText: "Cancel",
          type: "warning",
        }
      )
        .then(async () => {
          try {
            let result = await baseRequest.post(`/admin/delete-user/${row.id}`);
            let { data } = result;
            if (data.status == 200) {
              this.getAllUser();
              this.$message({
                type: "success",
                message: "Delete completed",
              });
            }
          } catch (error) {
            console.log(error);
            this.$message({
              type: "error",
              message: "Delete error",
            });
          }
        })
        .catch(() => {
          return;
        });
    },
    async handleChangePage (page) {
      this.page = page
      await this.getAllUser()
    },
    async getAllUser() {
      try {
        this.isLoading = true;
        const { data } = await baseRequest.get(`/admin/list-user?page_number=${this.page}&type=${this.selectFilter}&search=${this.search || ''}`);
        if (data.status == 200) {
          this.isLoading = false;
          const users = data?.data?.data || [];
          this.tableData = users.map((item) => ({
            id: item.id,
            name: item.name,
            email: item.email,
            phone: item.phone,
            is_admin: item.is_admin,
          }));
          this.isAdmin = data?.isAdmin;
          this.total = data?.data?.total;
          this.page = data?.data?.current_page;
          
        }
      } catch (error) {
        this.isLoading = false;
      }
    },
    async downloadExcel() {
      window.open(`${$Api.baseUrl}/api/download-excel`, '_blank');
    }
  },
  created() {
    this.getAllUser();
  },
  watch: {
    "search": {
      handler(value) {
        this.page = 1;
        this.total = 0;
        clearTimeout(this.timeOut)
        this.timeOut = setTimeout(async() => {
            await this.getAllUser()
        }, 300);
      }
    },
    "selectFilter": {
      handler(value) {
        clearTimeout(this.timeOut)
        this.timeOut = setTimeout(async() => {
            await this.getAllUser()
        }, 300);
      }
    }
  }
};
</script>
