<template>
  <div>
    <div class="container">
      <transition name="fade">
        <div class="w-full h-full" v-if="show">
          <div
            class="absolute inset-0 bg-blur flex items-center justify-center"
          >
            <div class="w-[95%] md:w-[70%] bg-white shadow-sm px-4 py-4">
              <div class="py-2 relative">
                <h1 class="font-semibold uppercase text-[14px]">
                  Create new Topic
                </h1>
                <span
                  class="absolute right-[5px] top-[5px] text-[20px] cursor-pointer"
                  @click="show = !show"
                >
                  <i class="lnr-cross"></i>
                </span>
              </div>
              <el-form :model="topicData" :rules="rules" ref="ruleForm">
                <div class="my-2">
                  <el-form-item label="Name Topic" prop="name">
                    <el-input
                      placeholder="Name Topic"
                      v-model="topicData.name"
                    ></el-input>
                  </el-form-item>
                </div>
                <div class="">
                  <el-form-item label="Description" prop="description">
                    <el-input
                      type="textarea"
                      placeholder="Description"
                      rows="3"
                      v-model="topicData.description"
                    ></el-input>
                  </el-form-item>
                </div>
                <div class="">
                  <el-form-item label="Exam" prop="isExam">
                    <el-switch v-model="topicData.isExam"></el-switch>
                  </el-form-item>
                </div>

                <div class="flex justify-end items-center mt-4">
                  <el-button plain @click="resetFeild">Cancel</el-button>
                  <el-button type="primary" @click="createTopic('ruleForm')"
                    >Create</el-button
                  >
                </div>
              </el-form>
            </div>
          </div>
        </div>
      </transition>
      <LoadingVue v-if="isLoading" />

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          class="bg-white shadow-sm flex items-center justify-between cursor-pointer py-4 px-4 text-[14px] font-semibold"
          :style="item.is_exam == 1 ? 'border: 4px solid #8bca4a !important' : ''"
          v-for="item in listTopic"
          :key="item.id"
        >
          <span class="w-[60%] overflow-hidden mr-2">{{ item.name }}</span>
          <div class="flex items-center">
            <el-button
              size="small"
              type="danger"
              plain
              icon="el-icon-delete"
              circle
              @click="deleteTopic(item.id)"
            ></el-button>
            <a
              :href="`${ApiUrl}/admin/volabulary-level-test/detail/${item.id}`"
              class="ml-2"
            >
              <el-button
                size="small"
                plain
                type="primary"
                icon="el-icon-edit"
                circle
              ></el-button>
            </a>
          </div>
        </div>
        <!-- <div
          class="bg-white shadow-sm flex items-center justify-center cursor-pointer py-4 px-4 text-[14px] font-semibold"
          @click="show = !show"
        >
          <div class="flex items-center">
            <div class="w-[32px] h-[32px] flex items-center justify-center">
              <i class="el-icon-plus text-[20px]"></i>
            </div>
          </div>
        </div> -->
      </div>
      <div class="mt-2 flex items-center justify-center"> 
        <el-pagination
          background
          layout="prev, pager, next"
          :total="total"
          :current-page.sync="current"
          :page-size="perPage"
          @prev-click="paginateClick"
          @next-click="paginateClick"
          @current-change="paginateClick"
        >
        </el-pagination>
      </div>
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
      show: false,
      topicData: {
        name: null,
        description: null,
        isExam: false
      },
      listTopic: [],
      ApiUrl: $Api.baseUrl,
      rules: {
        name: [
          {
            required: true,
            message: "Please input  name",
            trigger: "blur",
          },
        ],
        description: [
          {
            required: true,
            message: "Please input description",
            trigger: "blur",
          },
        ],
      },
      isLoading: false,
      total: 1,
      current: 1,
      pageSize: 1,
      perPage: 1,
    };
  },
  computed: {},
  watch: {},
  methods: {
    paginateClick(curentPage) {
      this.current = curentPage;
      this.getAllTopic();
    },
    resetFeild() {
      this.show = false;
      this.topicData = {
        name: null,
        description: null,
      };
    },
    async createTopic(formName) {
      this.$refs[formName].validate(async (valid) => {
        if (valid) {
          try {
            let rs = await baseRequest.post(
              `/admin/store-topic-vocabulary`,
              this.topicData
            );
            if (rs.data.status == 200) {
              this.getAllTopic();

              this.resetFeild();
              this.$message({
                type: "success",
                message: "Add successful topics",
              });
            } else {
              this.$message({
                type: "error",
                message: "Add error topics",
              });
            }
          } catch (e) {
            this.$message({
              type: "error",
              message: "Add error topics",
            });
          }
        } else {
          return false;
        }
      });
    },
    async getAllTopic() {
      try {
        this.isLoading = true;
        let result = await baseRequest.get(`/admin/list-topic-vocabulary?page=${this.current}`);
        console.log(result.data);
        if (result.data.status == 200) {
          setTimeout(() => {
            this.isLoading = false;
          }, 1000);
          this.listTopic = result.data.data.data;
          this.total = result.data.data.total;
          this.current = result.data.data.current_page;
          this.pageSize = result.data.data.last_page;
          this.perPage = result.data.data.per_page;
        }
      } catch (e) {
        setTimeout(() => {
          this.isLoading = false;
        }, 1000);
        console.log(e);
      }
    },
    async deleteTopic(id) {
      this.$confirm(
        "This will permanently delete the topic. Continue?",
        "Warning",
        {
          confirmButtonText: "OK",
          cancelButtonText: "Cancel",
          type: "warning",
        }
      )
        .then(async () => {
          try {
            let rs = await baseRequest.post(
              `/admin/delete-topic-vocabulary`,

              { id }
            );
            if (rs.data.status == 200) {
              this.getAllTopic();
              this.$message({
                type: "success",
                message: "Delete completed",
              });
            } else {
              this.$message({
                type: "error",
                message: "Delete error",
              });
            }
          } catch (e) {
            console.log(e);
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
  },

  created() {
    this.getAllTopic();
  },
};
</script>
<style scoped>
.bg-blur {
  background: rgba(0, 0, 0, 0.3);
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
