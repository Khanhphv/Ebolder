<template>
  <div>
    <LoadingVue v-if="isLoading" />
    <div class="container">
      <transition name="fade">
        <div class="w-full h-full" v-if="show">
          <div
            class="absolute inset-0 bg-blur flex items-center justify-center"
          >
            <div class="w-[95%] md:w-[70%] bg-white shadow-sm px-4 py-4">
              <div class="py-2 relative">
                <h1 class="font-semibold uppercase text-[14px]">
                  {{
                    state == "create"
                      ? "Tạo mới đề kiểm tra"
                      : "Cập nhật đề kiểm tra"
                  }}
                </h1>
                <span
                  class="absolute right-[5px] top-[5px] text-[20px] cursor-pointer"
                  @click="show = !show"
                >
                  <i class="lnr-cross"></i>
                </span>
              </div>

              <el-form :model="topicData" :rules="rules" ref="ruleForm">
                <div class="w-full h-[60vh] overflow-y-scroll">
                  <div class="my-2">
                    <el-form-item label="Đề kiểm tra" prop="name">
                      <el-input
                        placeholder="Đề kiểm tra"
                        v-model="topicData.name"
                      ></el-input>
                    </el-form-item>
                  </div>
                  <div class="my-2">
                    <el-form-item label="Exam" prop="isExam">
                      <el-switch v-model="topicData.isExam"></el-switch>
                    </el-form-item>
                  </div>
                  <div class="grid grid-cols-1 md:grid-cols-1 md:gap-4 gap-1">
                    <el-form-item label="Phần đọc" prop="valueReading">
                      <el-select
                        v-model="topicData.valueReading"
                        placeholder="Chọn đề"
                        style="width: 100%"
                      >
                        <el-option
                          v-for="item in listTopicReading"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                    <el-form-item label="Phần từ vựng" prop="valueVocabulary">
                      <el-select
                        v-model="topicData.valueVocabulary"
                        placeholder="Chọn đề"
                        style="width: 100%"
                      >
                        <el-option
                          v-for="item in listTopicVocabulary"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                    <el-form-item label="Phần nghe" prop="valueListening">
                      <el-select
                        v-model="topicData.valueListening"
                        placeholder="Chọn đề"
                        style="width: 100%"
                      >
                        <el-option
                          v-for="item in listTopicListening"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                    <el-form-item label="Phần ngữ pháp" prop="valueGrammar">
                      <el-select
                        v-model="topicData.valueGrammar"
                        placeholder="Chọn đề"
                        style="width: 100%"
                      >
                        <el-option
                          v-for="item in listTopicGrammar"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                    <el-form-item label="Phần luyện âm" prop="valueSpeaking">
                      <el-select
                        v-model="topicData.valueSpeaking"
                        placeholder="Chọn đề"
                        style="width: 100%"
                      >
                        <el-option
                          v-for="item in listTopicSpeaking"
                          :key="item.id"
                          :label="item.name"
                          :value="item.id"
                        >
                        </el-option>
                      </el-select>
                    </el-form-item>
                  </div>
                </div>
                <div class="flex justify-end items-center mt-4">
                  <el-button plain @click="resetFeild">Thoát</el-button>
                  <el-button
                    type="primary"
                    @click="
                      state == 'create'
                        ? createTopic('ruleForm')
                        : updateLevel('ruleForm')
                    "
                    >{{ state == "create" ? "Tạo mới" : "Cập nhật" }}</el-button
                  >
                </div>
              </el-form>
            </div>
          </div>
        </div>
      </transition>

      <div class="flex items-center justify-center mb-4">
        <el-button
          icon="el-icon-plus"
          plain
          @click="
            show = !show;
            state = 'create';
            topicData = {
              name: null,
              valueLesson: [],
              valueGrammar: null,
              valueReading: null,
              valueVocabulary: null,
              valueListening: null,
            };
          "
        ></el-button>
      </div>
      <div class="grid grid-cols-1 gap-4">
        <div
          class="bg-white shadow-sm flex items-center justify-between cursor-pointer py-4 px-4 text-[14px] font-semibold"
          v-for="item in listExam"
          :key="item.id"
        >
          <span class="w-[60%] overflow-hidden mr-2">{{ item.name }}</span>
          <div class="flex items-center">
            <el-button
              size="small"
              plain
              type="primary"
              icon="el-icon-edit"
              circle
              @click="editLevel(item.id)"
            ></el-button>
            <el-button
              class="ml-2"
              size="small"
              type="danger"
              plain
              icon="el-icon-delete"
              circle
              @click="deleteLevel(item.id)"
            ></el-button>
          </div>
        </div>
      </div>
    </div>
    <!-- <editor
            api-key="hri1xykfk0d1gnrwf70v71zn81p6f7s5e3z1edxly9mansfq"
            :init="{
                height: 500,
                menubar: false,
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount',
                ],
                toolbar:
                    'undo redo | formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | \
           bullist numlist outdent indent | removeformat | help',
            }"
        /> -->
  </div>
</template>

<script>
import baseRequest from "../../../utils/baseRequest";
import Editor from "@tinymce/tinymce-vue";
import LoadingVue from "../loading/Loading.vue";
export default {
  components: {
    Editor,
    LoadingVue,
  },
  data() {
    return {
      show: false,
      topicData: {
        id: null,
        name: null,
        valueLesson: [],
        valueGrammar: null,
        valueReading: null,
        valueVocabulary: null,
        valueListening: null,
        valueSpeaking: null,
        isExam: 0,

      },
      listExam: [],
      ApiUrl: $Api.baseUrl,
      rules: {
        name: [
          {
            required: true,
            message: "Please input  name",
            trigger: "blur",
          },
        ],
        valueSpeaking: [
          {
            required: true,
            message: "Please select speak",
            trigger: 'change'
          },
        ],
        valueVocabulary: [
          {
            required: true,
            message: "Please select vocabulary",
            trigger: 'change'
          },
        ],
        valueReading: [
          {
            required: true,
            message: "Please select reading",
            trigger: 'change'
          },
        ],
        listTopicGrammar: [
          {
            required: true,
            message: "Please select grammar",
            trigger: 'change'
          },
        ],
        valueListening: [
          {
            required: true,
            message: "Please select listening",
            trigger: 'change'
          },
        ],
      },

      listTopicGrammar: [],
      listTopicReading: [],
      listTopicVocabulary: [],
      listTopicListening: [],
      listTopicLesson: [],
      listTopicSpeaking: [],
      state: "create",
      idTemp: null,
      isLoading: false,
    };
  },
  computed: {},
  watch: {},
  methods: {
    async createTopic(formName) {
      this.$refs[formName].validate(async (valid,err) => {
        if (valid) {
          try {
            let dataTemp = {
              name: this.topicData.name,
              listening_id: this.topicData.valueListening,
              reading_id: this.topicData.valueReading,
              vocabulary_id: this.topicData.valueVocabulary,
              grammar_id: this.topicData.valueGrammar,
              speaking_id: this.topicData.valueSpeaking,
              status: this.topicData.isExam || false,
            };
            let rs = await baseRequest.post(`/admin/create-exam`, dataTemp);
            if (rs.data.status == 200) {
              this.resetFeild();
              this.$message({
                type: "success",
                message: "Thêm đề kiểm tra thành công",
              });
              this.getAllExam();
            } else {
              this.$message({
                type: "error",
                message: "Thêm cấp đề kiểm tra thất bại",
              });
            }
          } catch (e) {
            this.$message({
              type: "error",
              message: "Thêm cấp đề kiểm tra thất bại",
            });
          }
        } else {
          return false;
        }
      });
    },
    async updateLevel(formName, id) {
      this.$refs[formName].validate(async (valid) => {
        if (valid) {
          try {
            let dataTemp = {
              name: this.topicData.name,
              speaking_id: this.topicData.valueSpeaking,
              listening_id: this.topicData.valueListening,
              reading_id: this.topicData.valueReading,
              vocabulary_id: this.topicData.valueVocabulary,
              grammar_id: this.topicData.valueGrammar,
              status: this.topicData.isExam ,
            };
            let rs = await baseRequest.post(`/admin/update-exam/${this.idTemp}`, dataTemp);
            if (rs.data.status == 200) {
              this.resetFeild();
              this.$message({
                type: "success",
                message: "Sửa đề kiểm tra thành công",
              });
              this.getAllExam();
            } else {
              this.$message({
                type: "error",
                message: "Sửa đề kiểm tra thất bại",
              });
            }
          } catch (e) {
            this.$message({
              type: "error",
              message: "Sửa đề kiểm tra thất bại",
            });
          }
        } else {
          return false;
        }
      });
    },
    async getAllExam() {
      try {
        this.isLoading = true;
        let rs = await baseRequest.get(`/admin/get-all-exam`);
        if (rs.data.status == 200) {
          setTimeout(() => {
            this.isLoading = false;
          }, 1000);
          this.listExam = rs.data.data
            .map((item) => ({
              id: item.id,
              name: item.name,
            }))
            .reverse();
        }
      } catch (e) {
        console.log(e);
      } finally {
        this.isLoading = false;
      }
    },
    editLevel(id) {
      this.getDetailExam(id);
      this.state = "edit";
      this.idTemp = id;
    },
    async getDetailExam(id) {
      try {
        let rs = await baseRequest.get(`/admin/get-detail-exam/${id}`);
        let { data } = rs;
        if (data.status == 200) {
          this.topicData = {
            name: data.data.name || null,
            valueGrammar: data.data.listening_id,
            valueVocabulary: data.data.reading_id,
            valueReading: data.data.vocabulary_id,
            valueListening: data.data.grammar_id,
            valueSpeaking: data.data.speaking_id,
            isExam: data.data.status === 1 ? true : false
          };
          this.show = !this.show;
        }
      } catch (e) {
        console.log(e);
      }
    },
    async getAllTopicLesson() {
      try {
        let rs = await baseRequest.get(`/admin/list-topic-lesson`);
        if (rs.data.status == 200) {
          this.listTopicLesson = rs.data.data.map((item) => ({
            id: item.id,
            name: item.name,
            is_exam: item.is_exam || null,
          }));
        }
      } catch (e) {
        console.log(e);
      }
    },
    async getAllTopicVocabulary() {
      try {
        let rs = await baseRequest.get(`/admin/list-topic-vocabulary`);
        if (rs.data.status == 200) {
          this.listTopicVocabulary = rs.data.data.data
            .map((item) => ({
              id: item.id,
              name: item.name,
              is_exam: item.is_exam || null,
            }))
            .filter((itemTopic) => itemTopic.is_exam);
        }
      } catch (e) {
        console.log(e);
      }
    },
    async getAllTopicGrammar() {
      try {
        let rs = await baseRequest.get(`/admin/list-topic-grammar`);
        if (rs.data.status == 200) {
          this.listTopicGrammar = rs.data.data
            .map((item) => ({
              id: item.id,
              name: item.name,
              is_exam: item.is_exam || null,
            }))
            .filter((itemTopic) => itemTopic.is_exam);
        }
      } catch (e) {
        console.log(e);
      }
    },
    async getAllTopicReading() {
      try {
        let rs = await baseRequest.get(`/admin/list-topic-reading`);
        if (rs.data.status == 200) {
          this.listTopicReading = rs.data.data
            .map((item) => ({
              id: item.id,
              name: item.name,
              is_exam: item.is_exam || null,
            }))
            .filter((itemTopic) => itemTopic.is_exam);
        }
      } catch (e) {
        console.log(e);
      }
    },
    async getAllTopicListening() {
      try {
        let rs = await baseRequest.get(`/admin/topic-list-listening`);
        if (rs.data.status == 200) {
          this.listTopicListening = rs.data.data
            .map((item) => ({
              id: item.id,
              name: item.name,
              is_exam: item.is_exam || null,
            }))
            .filter((itemTopic) => itemTopic.is_exam);
        }
      } catch (e) {
        console.log(e);
      }
    },
    async getAllTopicSpeaking() {
      try {
        let rs = await baseRequest.get(`/admin/list-topic-speak`);
        if (rs.data.status == 200) {
          this.listTopicSpeaking = rs.data.data
            .map((item) => ({
              id: item.id,
              name: item.name,
              is_exam: item.is_exam || null,
            }))
            .filter((itemTopic) => itemTopic.is_exam);
        }
      } catch (e) {
        console.log(e);
      }
    },

    async deleteLevel(id) {
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
            let rs = await baseRequest.post(`/admin/delete-exam/${id}`);
            if (rs.data.status == 200) {
              this.getAllExam();
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
    resetFeild() {
      this.show = false;
      this.topicData = {
        name: null,
        valueLesson: [],
        valueGrammar: null,
        valueReading: null,
        valueVocabulary: null,
        valueListening: null,
      };
    },
  },

  created() {
    this.getAllTopicReading();
    this.getAllTopicLesson();
    this.getAllTopicVocabulary();
    this.getAllTopicGrammar();
    this.getAllTopicListening();
    this.getAllExam();
    this.getAllTopicSpeaking();
  },
};
</script>
<style scoped>
.bg-blur {
  background: #eee;
}
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
::-webkit-scrollbar {
  width: 5px;
}

/* Track */
::-webkit-scrollbar-track {
  background: #f1f1f1;
}

/* Handle */
::-webkit-scrollbar-thumb {
  background: #888;
}

/* Handle on hover */
</style>
