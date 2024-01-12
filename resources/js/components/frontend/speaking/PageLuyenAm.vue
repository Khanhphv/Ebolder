<template>
  <div class="w-full">
    <header-component :user="user" @openDialog="openDialog" />
    <div class="w-full max-w-[1206px] mx-auto p-4">
      <div
        class="bg-blur-f lg:px-[48px] lg:py-[48px] px-[16px] py-[16px]"
        v-show="isShowLabel"
      >
        <h2 class="text-[28px] font-semibold leading-[120%] text-center mb-4">
          <p>ENGLISH TALKING </p>
        </h2>
        <ul>
          <li class="list-disc text-[16px] mb-2">
            Read the text, then try to answer the questions
          </li>

        </ul>
        <!-- <h2 class="text-[24px] font-semibold leading-[120%] text-center mt-4">
          <VueCountdown
            :time="timeWork"
            @progress="handleCountdownProgress"
            ref="countdown"
          >
            <template slot-scope="props"
              >Time Remaining： {{ props.minutes }} minutes,
              {{ props.seconds }} seconds.</template
            >
          </VueCountdown>
        </h2> -->
      </div>
      <a href="/learn" style="text-decoration: none" v-show="!isShowLabel">
        <button
          class="cursor-pointer px-4 py-2 text-center uppercase leading-[28px] flex items-center justify-center font-light rounded-md bg-button text-[19px] text-white hover:opacity-80"
        >
          Back to learn page
        </button>
      </a>

      <transition name="el-zoom-in-top">
        <div
          class="bg-blur-f lg:px-[48px] lg:py-[48px] px-[16px] py-[16px] mt-4"
          v-show="!isShowLabel"
        >
          <h2 class="text-[28px] font-semibold leading-[120%] text-center mb-4">
            <!-- <p>
              You have reached {{ arrRightAns.length }} of
              {{ topic.questions.length }} point(s),
            </p> -->
          </h2>
          <div class="flex items-center justify-center">
            <!-- <el-progress
              type="circle"
              :percentage="
                Number(
                  ((arrRightAns.length / topic.questions.length) * 100).toFixed(
                    2
                  )
                )
              "
            ></el-progress> -->
          </div>
          <h2 class="text-[24px] font-semibold leading-[120%] text-center mt-4">
            <VueCountdown
              :auto-start="false"
              :time="timerun"
              @progress="handleCountdownProgress"
            >
              <template slot-scope="props"
                >Your time： {{ props.minutes }} minutes,
                {{ props.seconds }} seconds.</template
              >
            </VueCountdown>
          </h2>
        </div>
      </transition>
      <div class="bg-blur-f lg:px-[48px] px-[16px] py-[16px] mt-4">
        <h2 class="text-center text-xl mb-4">{{ data.name }}</h2>
        <div v-html="data.content" class="text-[16px]"></div>
      </div>
      <div class="bg-blur-f lg:px-[48px] px-[16px] py-[16px] mt-4" v-if="!isSaved">
        <div class="flex items-center justify-center w-full">
            <div id="startBtn" class="rounded-full w-16 h-16 bg-red-500 flex items-center justify-center" style="cursor: pointer;">
                <div style="width: 24px; height: 24px; border-radius: 100%; background-color: #fff;"></div>
    
            </div>
            <div id="stopBtn" class="rounded-full w-16 h-16 bg-red-500 flex items-center justify-center hidden" style="cursor: pointer;">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#FFF" style="width: 32px; height: 32px">
      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
    </svg>
            </div>
        </div>
        <audio
                        id="audio-preview"
                        class="w-full bg-transparent "
                        controls
                        disabled
                        hidden
                        ref="audio"
                    >
                    </audio>
                    <div id="audio-pp" class="flex flex-row items-center w-full hidden mt-4">
                        <button class="player-button">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                                fill="#FFF"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                        <input type="range" class="timeline" max="100" value="0" disabled></input>
                        <button class="sound-button">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#FFF">
        <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd" />
        </svg>
            </button>
                    </div>
      </div>
      <button
            id="button-save"
            @click="submit"
            class="
                cursor-pointer
                px-4
                py-2
                text-center
                uppercase
                leading-[28px]
                flex
                items-center
                justify-center
                font-light
                rounded-md
                bg-button
                text-[19px] text-white
                hover:opacity-80
                mt-4
                hidden
                mx-auto
         "
        >
        Save
     </button>
     <button
            v-if="isSaved"
            id="button-again"
            @click="preListenFileAudio"
            class="
                cursor-pointer
                px-4
                py-2
                text-center
                uppercase
                leading-[28px]
                flex
                items-center
                justify-center
                font-light
                rounded-md
                bg-button
                text-[19px] text-white
                hover:opacity-80
                mt-4
                mx-auto
         "
        >   
            Listen again
     </button>
    </div>

    <popup-dialog :dialogVisible="dialogVisible" @onClose="dialogVisible = false" />
    <!-- <footer-component /> -->
  </div>
</template>
  <script>
import VueCountdown from "@chenfengyuan/vue-countdown";
import baseRequest from "../../../utils/baseRequest";
export default {
  props: ["data", "query", "user", "request"],
  data() {
    return {
      answerData: [],
      topic: {},
      total: 0,
      isShowLabel: true,
      timeWork: 45 * 60 * 1000,
      timerun: 0,
      indexPage: 0,
      isRecord: false,
      file: null,
      audio_src: null,
      isSaved: false,
      dialogVisible: false,
    };
  },
  components: {
    VueCountdown,
  },
  methods: {
    openDialog() {
      this.dialogVisible = true;
    },
    handleCountdownProgress(data) {
      this.timerun = this.timeWork - data.totalMilliseconds + 1000;
      if (this.timerun === this.timeWork) {
        this.submit();
      }
    },
    customAudio() {
      const playerButton = document.querySelector(".player-button"),
        audio = document.querySelector("audio"),
        playIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#FFF">
    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
  </svg>
      `,
        pauseIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#FFF">
  <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
</svg>
      `;
      function toggleAudio() {
        console.log(audio);
        if (audio.paused) {
          audio.play();
          playerButton.innerHTML = pauseIcon;
        } else {
          audio.pause();
          playerButton.innerHTML = playIcon;
        }
      }
      playerButton.addEventListener("click", toggleAudio);
      function audioEnded() {
        playerButton.innerHTML = playIcon;
      }

      audio.onended = audioEnded;
      const timeline = document.querySelector(".timeline");
      function changeTimelinePosition() {
        const percentagePosition = (100 * audio.currentTime) / audio.duration;
        timeline.style.backgroundSize = `${percentagePosition}% 100%`;
        timeline.value = percentagePosition;
      }

      audio.ontimeupdate = changeTimelinePosition;
      function changeSeek() {
        const time = (timeline.value * audio.duration) / 100;
        audio.currentTime = time;
      }

      timeline.addEventListener("change", changeSeek);
      const soundButton = document.querySelector(".sound-button"),
        soundIcon = `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#FFF">
  <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd" />
</svg>`,
        muteIcon = `
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#FFF">
  <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd" />
</svg>`;
      function toggleSound() {
        audio.muted = !audio.muted;
        soundButton.innerHTML = audio.muted ? muteIcon : soundIcon;
      }

      soundButton.addEventListener("click", toggleSound);
    },
    async submit() {
      let formData = new FormData();
      formData.append("file", this.file);
      formData.append("user_id", this.user.id);
      formData.append("test_id", this.query.testId);
      formData.append("email", this.user.email);
      if(!this.request.exam){
        formData.append("level_id", this.query.levelId);
      }else {
        formData.append("exam_id", this.request.examId);
      }
      const headers = {
        "Content-Type": "multipart/form-data",
      };
      try {
        let rs = await baseRequest.post(
          `/admin/save-result-exam-luyen-am`,
          formData,
          { headers }
        );
        if (rs.data.status === 200) {
          this.$message({
            type: "success",
            message: rs.data.message,
          });
          await this.saveHistory();
          await this.checkHistoryExam();
        }
      } catch (e) {
        console.log(e);
        this.$message({
          type: "error",
          message: "Delete error",
        });
      }
    },
    onRecordingFinished(blob) {
      var file = new File(
        [blob],
        `${this.user.email}-${this.query.testId}.wav`
      );
      this.file = file;
    },
    async saveHistory() {
      let dataHistory = {
        test_type: "Talking",
        topic_name: this.data.name,
        scores: `5/5`,
        completion_time: "not require",
        exam_id: this.query.testId,
        level_id: this.query.levelId,
      };
      if (this.request.exam) {
        dataHistory.exam_final_id = this.request.examId;
        if (this.request.exam) {
          try {
            let result = await baseRequest.post(
              "/admin/save-exam-history-final",
              {
                id: this.request.historyId,
                result_talking: `Done`,
                time: this.timerun,
              }
            );
            if (result.data.status === 200) {
              window.location.href = `${$Api.baseUrl}/exam/result/${this.request.historyId}&v=${this.request.v}&g=${this.request.g}&l=${this.request.l}&s=${this.request.s}&r=${this.request.r}&w=${this.request.w}&historyId=${this.request.historyId}&examId=${this.request.examId}&exam=true`;
            }
          } catch (error) {}
        }
      }
      try {
        let result = await baseRequest.post("/admin/save-history", dataHistory);
      } catch (e) {
        console.log("🚀 ~ file: ListeningTest.vue:679 ~ submit ~ e", e);
      }
    },
    async checkHistoryExam() {
      try {
        let config = {
          type: "Talking",
          exam_id: this.query.testId,
        };
        if (this.request.exam) {
          config.exam_final_id = this.request.examId;
          config.status = "exam";
        } else {
          config.level_id = this.query.levelId;
          config.status = "learn";
        }
        let result = await baseRequest.post(
          "/admin/check-history-exam",
          config
        );
        result = result.data;
        if (result.status === 200 && result.data !== null) {
          const startBtn = document.getElementById("startBtn");
          const stopBtn = document.getElementById("stopBtn");
          const audioPp = document.getElementById("audio-pp");
          const buttonSave = document.getElementById("button-save");

          startBtn.classList.add("hidden");
          stopBtn.classList.add("hidden");
          audioPp.classList.add("hidden");
          buttonSave.classList.add("hidden");
          this.isSaved = true;
          this.getAudioSaved();
        }
      } catch (error) {
        console.log(
          "🚀 ~ file: VocabularyTest.vue:456 ~ checkHistoryExam ~ error:",
          error
        );
      }
    },
    async getAudioSaved() {
      try {
        const config = {
            user_id: this.user.id,
            exam_id: this.query.testId,
        }

        if (this.request.exam) {
            config.exam_final_id = this.request.examId
        
        }else {
            config.level_id = this.query.levelId
        }
        let result = await baseRequest.post("/admin/get-result-exam-luyen-am", config);
        if (result.data.status === 200) {
          this.audio_src = result.data.data.url_audio;
        }
      } catch (error) {
        console.log(
          "🚀 ~ file: VocabularyTest.vue:456 ~ checkHistoryExam ~ error:",
          error
        );
      }
    },
    preListenFileAudio() {
      let tempArr = "";
      tempArr = this.audio_src.split("/").map((item, index) => {
        if (index !== 0) {
          return item;
        }
      });
      window.location.href = tempArr.join("/");
    },
  },
  created() {
    this.checkHistoryExam();
  },
  mounted() {
    const _this = this;
    this.customAudio();
    const startBtn = document.getElementById("startBtn");
    const stopBtn = document.getElementById("stopBtn");
    const recordedAudio = document.getElementById("audio-preview");
    const audioPp = document.getElementById("audio-pp");
    const buttonSave = document.getElementById("button-save");
    let mediaRecorder;
    let chunks = [];

    function startRecording() {
      navigator.mediaDevices.getUserMedia({ audio: true }).then((stream) => {
        mediaRecorder = new MediaRecorder(stream);
        console.log(mediaRecorder);
        mediaRecorder.start();
        startBtn.disabled = true;
        stopBtn.disabled = false;
        startBtn.classList.add("hidden");
        stopBtn.classList.remove("hidden");
        audioPp.classList.add("hidden");
        buttonSave.classList.add("hidden");

        setTimeout(() => {
          stopRecording();
        }, 120000); // giới hạn thời gian ghi âm trong 2 phút
        mediaRecorder.ondataavailable = (e) => {
          chunks = [];
          chunks.push(e.data);
        };

        mediaRecorder.onstop = () => {
          stream
            .getTracks() // get all tracks from the MediaStream
            .forEach((track) => track.stop());
          console.log(stream.getTracks());
          const blob = new Blob(chunks, { type: "audio/wav" });
          _this.onRecordingFinished(blob);
          const url = URL.createObjectURL(blob);
          recordedAudio.src = "";
          recordedAudio.src = url;
          startBtn.classList.remove("hidden");
          stopBtn.classList.add("hidden");
          audioPp.classList.remove("hidden");
          buttonSave.classList.remove("hidden");
        };
      });
    }
    function resetRecording() {
      stopRecording();
      chunks = [];
      recordedAudio.src = "";
      timeCount = 0;
      startBtn.disabled = false;
      stopBtn.disabled = true;
    }
    function stopRecording() {
      mediaRecorder.stop();
      startBtn.disabled = false;
      stopBtn.disabled = true;
    }

    startBtn.addEventListener("click", startRecording);
    stopBtn.addEventListener("click", stopRecording);
  },
};
</script>
  <style>
.border-answer {
  border-color: #eee;
}
.border-answer.active {
  border-color: #5b5ea6;
  border-width: 3px;
}
.border-answer.active.right {
  border-color: #009b77;
  border-width: 3px;
}
.border-answer.wrong.right_wait {
  border-color: #009b77;
  border-width: 3px;
}
.border-answer.active.right_wait.bgright {
  background-color: #009b77;
}
.border-answer.active.wrong {
  border-color: #dd4124;
  border-width: 3px;
}
.text-answer {
  color: #eee;
}
.text-answer.active {
  color: #5b5ea6;
}
.text-answer.active.right {
  color: #009b77;
}
.text-answer.wrong.right_wait {
  color: #009b77;
}
.text-answer.active.wrong {
  color: #dd4124;
}
.text-answer.active.right_wait.bgright {
  color: #fff;
}
</style>
  