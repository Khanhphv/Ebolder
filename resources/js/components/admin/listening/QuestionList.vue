<template>
  <div>
    <div class="container">
      <LoadingVue v-if="isLoading" />
      <div class="grid grid-cols-1 gap-4">
        <input class="
              border
              border-[#e6e8ec]
              outline-none
              rounded-md
              w-[30%]
              px-2
              py-2
              mb-2
          " v-model="textSearch" placeholder="Search" > </input>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div
          class="bg-white shadow-sm flex items-center justify-between cursor-pointer py-4 px-4 text-[14px] font-semibold"
          v-for="(item, index) in listAudio"
          :key="item.id"
        >
          <span class="w-[60%] overflow-hidden mr-2">{{ item.name }}</span>
          <div class="flex items-center">
            <div ref="play">
              <!-- <el-button
                size="small"
                type="success"
                plain
                icon="el-icon-video-play"
                class="text-[20px]"
                circle
                @click="
                  playAudio(`${baseApi}/upload/audio/${item.audio}`, index, item)
                "
              ></el-button> -->
            </div>
            <div ref="pause" class="hidden">
              <el-button
                size="small"
                type="warning"
                plain
                icon="el-icon-video-pause"
                class="text-[20px]"
                circle
                @click="pauseAudio(index)"
              ></el-button>
            </div>
            <a
              :href="`${ApiUrl}/admin/listening-level-test/question-edit/${item.id}`"
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
            <el-button
              class="ml-2"
              size="small"
              type="danger"
              plain
              icon="el-icon-delete"
              circle
              @click="deleteAudio(item.id)"
            ></el-button>
          </div>
        </div>
      </div>
      <div class="mt-2 flex items-center justify-center"> 
        <el-pagination background layout="prev, pager, next" v-model="page" :page-size="10" :total="total" @current-change="handleChangePage" />
      </div>
      <div class="absolute bottom-2 left-[50%] bg-white w-[500px]">
        <audio
          hidden
          id="audio-preview"
          autoplay
          class="w-full"
          controls
          v-show="file != ''"
        />
      </div>
    </div>
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
        name: null,
        description: null,
      },
      listAudio: [],
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
      file: null,
      baseApi: $Api.baseUrl,
      isplay: false,
      isLoading: false,
      total : 0,
      page: 1,
      textSearch: '',
      timeOut: null
    };
  },
  computed: {},
  watch: {},
  methods: {
    playAudio(audioMusic, index) {
      let audio = document.getElementById("audio-preview");
      audio.src = audioMusic;
      this.$refs.play[index].classList.add("hidden");
      this.$refs.pause[index].classList.remove("hidden");
      this.$refs.pause[index].classList.add("block");
      for (let i = 0; i < this.$refs.pause.length; i++) {
        if (i != index) {
          this.$refs.play[i].classList.remove("hidden");
          this.$refs.play[i].classList.add("block");
          this.$refs.pause[i].classList.remove("block");
          this.$refs.pause[i].classList.add("hidden");
        }
      }
    },
    pauseAudio(index) {
      let audio = document.getElementById("audio-preview");
      audio.pause();
      this.$refs.play[index].classList.remove("hidden");
      this.$refs.play[index].classList.add("block");
      this.$refs.pause[index].classList.remove("block");
      this.$refs.pause[index].classList.add("hidden");
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
              this.getAllAudio();

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
    async handleChangePage (page) {
      this.page = page
      await this.getAllAudio()
    },
    async getAllAudio() {
      try {
        this.isLoading = true;
        const { data } = await baseRequest.get(`/admin/get-audio-listening?search=${this.textSearch}&page_number=${this.page}`);
        if (data.status == 200) {
          this.isLoading = false;
          this.listAudio = data.data.data;
          this.total = data?.data?.total;
          this.page = data?.data?.current_page
        }
      } catch (e) {
        this.isLoading = false;
      }
    },
    async deleteAudio(id) {
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
            let rs = await baseRequest.post(`/admin/delete-audio-listening`, {
              id,
            });
            if (rs.data.status == 200) {
              this.getAllAudio();
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
    this.getAllAudio();
  },
  watch: {
    "textSearch": {
        handler(value) {
            this.page = 1;
            clearTimeout(this.timeOut)
            this.timeOut = setTimeout(async() => {
                await this.getAllAudio()
            }, 300);
        }
    }
  }
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
