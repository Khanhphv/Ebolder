<template>
    <div class="w-full">
        <header-component :user="user" @openDialog="openDialog" />
        <div class="w-full max-w-[1206px] mx-auto p-4">
            <div class="bg-blur-f p-[8px] lg:px-[48px] lg:py-[48px]" v-show="isShowLabel || !request.exam">
                <h2
                    class="
                        text-[28px]
                        font-semibold
                        leading-[120%]
                        text-center
                        mb-4
                    "
                >
                <p>Listening</p>
                </h2>
                <h2
                    class="
                        text-[24px]
                        font-semibold
                        leading-[120%]
                        text-center
                        mt-4
                    "
                >
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
                </h2>
            </div>
            <a href="/learn" style="text-decoration: none;"  v-show="!isShowLabel && !request.exam">

                <button
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
                        "
                    >
                        Back to learn page
                    </button>
            </a>
            <transition name="el-zoom-in-top">
                <div
                    class="bg-blur-f p-[8px] lg:px-[48px] lg:py-[48px] mt-4"
                    v-show="!isShowLabel"
                >
                    <h2
                        class="
                            text-[28px]
                            font-semibold
                            leading-[120%]
                            text-center
                            mb-4
                        "
                    >
                        <p>
                            You have reached {{ arrRightAns.length }} of
                            {{ answerData.length }} point(s),
                        </p>
                    </h2>
                    <div class="flex items-center justify-center">
                        <el-progress
                            type="circle"
                            :percentage="
                                Number(
                                    (
                                        (arrRightAns.length /
                                            answerData.length) *
                                        100
                                    ).toFixed(2)
                                )
                            "
                        ></el-progress>
                    </div>
                    <h2
                        class="
                            text-[24px]
                            font-semibold
                            leading-[120%]
                            text-center
                            mt-4
                        "
                    >
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
            <div
                v-for="(topicItem, indexAudio) in topic"
                :key="topicItem.id"
                v-if="indexAudio == indexPage"
            >
            <div v-html="topicItem.content" class="bg-blur-f p-[8px] mt-4">
                    </div>
                <p class="text-[16px] mt-4">
                    Audio: <strong>{{ indexAudio + 1 }}</strong> of
                    <strong>{{ topic.length }}</strong>
                </p>
                <div class="bg-blur-f px-[16px] py-[16px] lg:px-[48px] lg:py-[16px] mt-4">
                    <audio
                        id="audio-preview"
                        class="w-full bg-transparent"
                        controls
                        disabled
                        hidden
                    >
                        <source
                            :src="`${baseURl}/upload/audio/${topicItem.audio}`"
                            type="audio/mpeg"
                        />
                    </audio>
                    <div class="controls">
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

                <div
                    v-if="isShowLabel"
                    class="w-full mt-4"
                    v-for="(question, index) in topicItem.questions"
                    :key="question.id"
                >

                    <p class="text-[16px]">
                        Question <strong>{{ index + 1 }}</strong> of
                        <strong>{{ topicItem.questions.length }}</strong>
                    </p>
                    <p class="mt-2 mb-4 text-[16px]" v-if="question.type == 1">{{ question.question }}</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" v-if="question.type == 1">
                        <div
                            class="w-full"
                            v-for="(item, indexItem) in question.dataAns"
                            :key="item.id"
                        >
                            <input
                                type="radio"
                                :id="`test${item.id}`"
                                :name="`radio-group${index}`"
                                checked
                                v-model="
                                    answerData.find(
                                        (itemi) => itemi.id == question.id
                                    ).radioValue
                                "
                                :value="item.id"
                                hidden
                                :disabled="isShowLabel == false"
                            />
                            <label
                                :for="`test${item.id}`"
                                class="
                                    flex
                                    justify-between
                                    items-center
                                    w-full
                                    py-2
                                    px-4
                                    border-2
                                    rounded-lg
                                    border-answer
                                "
                                :class="[
                                    answerData.find(
                                        (itemi) => itemi.id == question.id
                                    ).radioValue == item.id
                                        ? `active`
                                        : '',
                                ]"
                            >
                                <div
                                    class="text-[16px] font-semibold"
                                    :style="
                                        answerData.find(
                                            (itemi) => itemi.id == question.id
                                        ).radioValue == item.id
                                            ? `color: #5B5EA6`
                                            : 'color: #eee'
                                    "
                                >
                                    {{ item.text }}
                                </div>
                                <div>
                                    <AVue
                                        :isActive="
                                            answerData.find(
                                                (itemi) =>
                                                    itemi.id == question.id
                                            ).radioValue == item.id
                                                ? true
                                                : false
                                        "
                                        v-if="indexItem == 0"
                                    />
                                    <BVue
                                        :isActive="
                                            answerData.find(
                                                (itemi) =>
                                                    itemi.id == question.id
                                            ).radioValue == item.id
                                                ? true
                                                : false
                                        "
                                        v-if="indexItem == 1"
                                    />
                                    <CVue
                                        :isActive="
                                            answerData.find(
                                                (itemi) =>
                                                    itemi.id == question.id
                                            ).radioValue == item.id
                                                ? true
                                                : false
                                        "
                                        v-if="indexItem == 2"
                                    />
                                    <DVue
                                        :isActive="
                                            answerData.find(
                                                (itemi) =>
                                                    itemi.id == question.id
                                            ).radioValue == item.id
                                                ? true
                                                : false
                                        "
                                        v-if="indexItem == 3"
                                    />
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="flex mt-2 mb-4 text-[16px] mt-4 items-center"  v-if="question.type == 2">
                    <div
                        v-for="(item, indexAns) in arrQuestion(question)"
                        :key="indexAns"
                    >
                        <input
                            type="text"
                            class="
                                mx-2
                                text-black
                                border-none
                                outline-none
                                rounded-md
                                w-[86px]
                                lg:w-[150px]
                                px-2
                                py-1
                            "
                            v-model="
                                answerData.find(item => item.id === question.id).dataChoose[
                                    getIndexSharp(question, indexAns)
                                ].radioValue
                            "
                            v-if="item == '#'"
                        />
                        <span v-else class="mr-1">{{ item }}</span>
                    </div>
                </div>
                </div>

                <div
                    v-if="!isShowLabel"
                    class="w-full mt-4"
                    v-for="(question, index) in topicItem.questions"
                    :key="question.id"
                >
                    <p class="text-[16px]">
                        Question <strong>{{ index + 1 }}</strong> of
                        <strong>{{ topicItem.questions.length }}</strong>
                    </p>
                    <p class="mt-2 mb-4 text-[16px]" v-if="question.type == 1">{{ question.question }}</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" v-if="question.type == 1">
                        <div
                            class="w-full"
                            v-for="(item, indexItem) in question.dataAns"
                            :key="item.id"
                        >
                            <input
                                type="radio"
                                :id="`test${item.id}`"
                                :name="`radio-group${index}`"
                                checked
                                v-model="
                                    answerData.find(
                                        (itemi) => itemi.id == question.id
                                    ).radioValue
                                "
                                :value="item.id"
                                hidden
                                :disabled="isShowLabel == false"
                            />
                            <label
                                :for="`test${item.id}`"
                                class="
                                    flex
                                    justify-between
                                    items-center
                                    w-full
                                    py-2
                                    px-4
                                    border-2
                                    rounded-lg
                                    border-answer
                                    min-h-[70px]
                                "
                                :class="[
                                    answerData.find(
                                        (itemi) => itemi.id == question.id
                                    ).radioValue == item.id
                                        ? `active`
                                        : '',
                                    answerData.find(
                                        (itemi) => itemi.id == question.id
                                    ).radioValue ==
                                    answerData.find(
                                        (itemi) => itemi.id == question.id
                                    ).right_answer
                                        ? `right`
                                        : 'wrong',
                                    item.id ==
                                    answerData.find(
                                        (itemi) => itemi.id == question.id
                                    ).right_answer
                                        ? `right_wait`
                                        : '',
                                    item.id ==
                                        answerData.find(
                                            (itemi) => itemi.id == question.id
                                        ).right_answer &&
                                    item.id ==
                                        answerData.find(
                                            (itemi) => itemi.id == question.id
                                        ).radioValue
                                        ? 'bgright'
                                        : '',
                                ]"
                            >
                                <div
                                    class="
                                        text-[16px]
                                        font-semibold
                                        text-answer
                                    "
                                    :class="[
                                        answerData.find(
                                            (itemi) => itemi.id == question.id
                                        ).radioValue == item.id
                                            ? `active`
                                            : '',
                                        answerData.find(
                                            (itemi) => itemi.id == question.id
                                        ).radioValue ==
                                        answerData.find(
                                            (itemi) => itemi.id == question.id
                                        ).right_answer
                                            ? `right`
                                            : 'wrong',
                                        item.id ==
                                        answerData.find(
                                            (itemi) => itemi.id == question.id
                                        ).right_answer
                                            ? `right_wait`
                                            : '',
                                        item.id ==
                                            answerData.find(
                                                (itemi) =>
                                                    itemi.id == question.id
                                            ).right_answer &&
                                        item.id ==
                                            answerData.find(
                                                (itemi) =>
                                                    itemi.id == question.id
                                            ).radioValue
                                            ? 'bgright'
                                            : '',
                                    ]"
                                >
                                    {{ item.text }}
                                </div>
                                <div>
                                    <el-button
                                        v-if="
                                            item.id ==
                                            answerData.find(
                                                (itemi) =>
                                                    itemi.id == question.id
                                            ).right_answer
                                        "
                                        type="success"
                                        icon="el-icon-check"
                                        circle
                                        size="mini"
                                    ></el-button>
                                    <div v-else>
                                        <AVue
                                            :isActive="
                                                answerData.find(
                                                    (itemi) =>
                                                        itemi.id == question.id
                                                ).radioValue == item.id
                                                    ? true
                                                    : false
                                            "
                                            v-if="indexItem == 0"
                                        />
                                        <BVue
                                            :isActive="
                                                answerData.find(
                                                    (itemi) =>
                                                        itemi.id == question.id
                                                ).radioValue == item.id
                                                    ? true
                                                    : false
                                            "
                                            v-if="indexItem == 1"
                                        />
                                        <CVue
                                            :isActive="
                                                answerData.find(
                                                    (itemi) =>
                                                        itemi.id == question.id
                                                ).radioValue == item.id
                                                    ? true
                                                    : false
                                            "
                                            v-if="indexItem == 2"
                                        />
                                        <DVue
                                            :isActive="
                                                answerData.find(
                                                    (itemi) =>
                                                        itemi.id == question.id
                                                ).radioValue == item.id
                                                    ? true
                                                    : false
                                            "
                                            v-if="indexItem == 3"
                                        />
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="flex mt-2 mb-4 text-[16px] mt-4 items-center"  v-if="question.type == 2">
                    <div
                        v-for="(item, indexAns) in arrQuestion(question)"
                        :key="indexAns"
                    >
                        <input
                            type="text"
                            class="
                                mx-2
                                text-white
                                border-none
                                outline-none
                                rounded-md
                                w-[86px]
                                lg:w-[150px]
                                px-2
                                py-1
                            "
                            v-model="
                                answerData.find(item => item.id === question.id).dataChoose[
                                    getIndexSharp(question, indexAns)
                                ].radioValue
                            "
                            :class="[
                                answerData.find(item => item.id === question.id).dataChoose[
                                    getIndexSharp(question, indexAns)
                                ].radioValue?.trim()?.toLowerCase() ==
                                answerData.find(item => item.id === question.id).dataRight[
                                    getIndexSharp(question, indexAns)
                                ].right_answer?.trim()?.toLowerCase()
                                    ? 'right-ans'
                                    : 'wrong-ans',
                            ]"
                            v-if="item == '#'"
                            disabled
                        />
                        <span v-else class="mr-1">{{ item }}</span>
                    </div>
                </div>

                </div>
            </div>
            <div class="flex items-center justify-between mt-4">
                <div v-if="indexPage == 0"></div>
                <button
                    v-if="indexPage > 0"
                    @click="movePage(-1)"
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
                    "
                >
                    Prev
                </button>
                <button
                    v-show="indexPage != topic.length - 1 && topic.length > 0"
                    @click="movePage(1)"
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
                    "
                >
                    Next
                </button>
                <button
                    v-if="isShowLabel == true && indexPage == topic.length - 1 || (topic.length === 0 && request.exam)"
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
                    "
                >
                    Finish
                </button>
            </div>
        </div>

        <popup-dialog :dialogVisible="dialogVisible" @onClose="dialogVisible = false" />
        <!-- <footer-component /> -->
    </div>
</template>
<script>
import AVue from "../alphabet/A.vue";
import BVue from "../alphabet/B.vue";
import CVue from "../alphabet/C.vue";
import DVue from "../alphabet/D.vue";
import VueCountdown from "@chenfengyuan/vue-countdown";
import baseRequest from "../../../utils/baseRequest";
export default {
  props: ["data", "query", "user", "request"],
  data() {
    return {
      dialogVisible: false,
      answerData: [],
      topic: [],

      arrRightAns: [],
      arrWrongAns: [],
      total: 0,
      isShowLabel: true,
      timeWork: 45 * 60 * 1000,
      timerun: 0,
      indexPage: 0,
      baseURl: $Api.baseUrl,
    };
  },
  components: {
    AVue,
    BVue,
    CVue,
    DVue,
    VueCountdown,
  },
  watch: {
    indexPage() {
      setTimeout(() => {
        this.customAudio();
      }, 100);
    },
  },
  methods: {
    openDialog() {
      this.dialogVisible = true;
    },
    async submit() {
      this.$refs.countdown.abort();
      this.topic.forEach((itemTopic) => {
        itemTopic.questions.forEach((item) => {
          this.answerData.find(
            (itemAns) => itemAns.id == item.id
          ).right_answer = item.right_answer;
        });
      });

      this.answerData.forEach((item) => {
        if (item.type == 1) {
          if (item.radioValue == item.right_answer) {
            this.arrRightAns.push(item);
          }
        } else {
          const sameArray =
            item.dataChoose.length === item.dataRight.length &&
            item.dataChoose.every(
              (value, index) =>
                value.radioValue?.trim()?.toLowerCase() === item.dataRight[index].right_answer?.trim()?.toLowerCase()
            );
          if (sameArray) {
            this.arrRightAns.push(item);
          }
        }
      });
      let data_exam = {
        answerData: this.answerData,
        arrRightAns: this.arrRightAns,
      };
      data_exam = JSON.stringify(data_exam);
      let dataHistory = {
        test_type: "Listening",
        topic_name: this.data.name,
        scores: `${this.arrRightAns.length}/${this.answerData.length}`,
        completion_time: this.timerun,
        content_exam: data_exam,
        exam_id: this.data.id,
        level_id: this.query.levelId,
      };
      if (this.request.exam) {
        dataHistory.exam_final_id = this.request.examId;
        dataHistory.no_exam = false;
        try {
          let result = await baseRequest.post(
            "/admin/save-history",
            dataHistory
          );
        } catch (e) {
          console.log("🚀 ~ file: ListeningTest.vue:679 ~ submit ~ e", e);
        }
      }else {
        dataHistory.no_exam = true;
        try {
          let result = await baseRequest.post(
            "/admin/save-history",
            dataHistory
          );
        } catch (e) {
          console.log("🚀 ~ file: ListeningTest.vue:679 ~ submit ~ e", e);
        }
      }
      if (this.request.exam) {
        try {
          let result = await baseRequest.post(
            "/admin/save-exam-history-final",
            {
              id: this.request.historyId,
              result_listening: `${this.arrRightAns.length}/${this.answerData.length}`,
              time: this.timerun,
            }
          );
          if (result.data.status === 200) {
            window.location.href = `${$Api.baseUrl}/english-level-test/Speaking?testId=${this.request.s}&l=${this.request.l}&s=${this.request.s}&r=${this.request.r}&w=${this.request.w}&historyId=${this.request.historyId}&examId=${this.request.examId}&exam=true`;
          }
        } catch (error) {}
      }
      window.scrollTo({ top: 0, behavior: "smooth" });
      setTimeout(() => {
        this.isShowLabel = false;
      }, 800);
    },
    handleCountdownProgress(data) {
      this.timerun = this.timeWork - data.totalMilliseconds + 1000;
      if (this.timerun === this.timeWork) {
        this.submit();
      }
    },
    movePage(number) {
      switch (number) {
        case -1: {
          if (this.indexPage > 0) {
            this.indexPage += number;
          }
          return;
        }
        case 1: {
          if (this.indexPage < this.topic.length - 1) {
            this.indexPage += number;
          }
          return;
        }
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
    arrQuestion(question) {
      return question.question.split(" ");
    },
    getIndexSharp(question, index) {
      let arr = question.question.split(" ").splice(0, index + 1);
      return arr.filter((e) => e == "#").length - 1;
    },
    renderInput(question, index) {
      let sum = 0;
      let arrQuestion = question.question.split(" ");
      for (let i = 0; i < arrQuestion.length; i++) {
        if (arrQuestion[i] == "#") {
          sum++;
          arrQuestion[i] = `<input
                            style="color:black;
                                border: none;
                                outline:none;
                                border-radius:
                                5px; padding: 2px .5em;
                                background: #e2e7ed;
                                display: inline-block;
                                width: 100px"
                                v-model='${
                                  this.answerData.find(item => item.id === question.id).dataChoose[sum - 1]
                                    .radioValue
                                }'>`;
        }
      }
      return arrQuestion.join(" ");
    },
    async checkHistoryExam() {
      try {
        let config = {
          type: "Listening",
          exam_id: this.data.id,
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
          this.$refs.countdown.abort();
          this.timerun = parseInt(result.data.completion_time);
          let data_exam = JSON.parse(result.data.content_exam);
          this.answerData = data_exam.answerData;
          this.arrRightAns = data_exam.arrRightAns;
          this.isShowLabel = false;
        }
      } catch (error) {
        console.log(
          "🚀 ~ file: VocabularyTest.vue:456 ~ checkHistoryExam ~ error:",
          error
        );
      }
    },
  },
  created() {
    this.topic = this.data.topic_audio_listen.map((audio) => ({
      id: audio.id,
      audio: audio.audio,
      content: audio.content,
      questions: audio.question_listening.map((question) => ({
        id: question.id,
        question: question.question,
        type: question.type,
        dataAns: question.answer_listening.map((answer) => ({
          id: answer.answer_id,
          text: answer.text,
        })),

        right_answer: question.right_answers
          ? question.right_answers.answer_id
          : "",
      })),
    }));
    this.topic.forEach((itemTopic) => {
      itemTopic.questions.forEach((item) => {
        if (item.type == 1) {
          this.answerData.push({
            radioValue: null,
            right_answer: "",
            type: 1,
            id: item.id,
          });
        } else {
          this.answerData.push({
            dataChoose: item.dataAns.map((item, index) => ({
              radioValue: null,
            })),
            dataRight: item.dataAns.map((item, index) => ({
              right_answer: item.text,
            })),
            type: 2,
            id: item.id,
          });
        }
      });
      if (this.request.exam) {
        this.checkHistoryExam();
      }
    });
  },
  mounted() {
    this.customAudio();
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

.controls {
  display: flex;
  flex-direction: row;
  align-items: center;
  width: 100%;
}
.player-button {
  background-color: transparent;
  border: 0;
  width: 3em;
  height: 3em;
  cursor: pointer;
  padding: 0;
}
.timeline {
  -webkit-appearance: none;
  width: calc(100% - 3em);
  height: 0.5em;
  background-color: #e5e5e5;
  border-radius: 5px;
  background-size: 0% 100%;
  background-image: linear-gradient(#5b5ea6, #5b5ea6);
  background-repeat: no-repeat;
  margin: 0 16px;
}
.timeline::-webkit-slider-thumb {
  -webkit-appearance: none;
  width: 1em;
  height: 1em;
  border-radius: 50%;
  cursor: pointer;
  opacity: 0;
  transition: all 0.1s;
  background-color: #5b5ea6;
}

.timeline::-webkit-slider-runnable-track {
  -webkit-appearance: none;
  box-shadow: none;
  border: none;
  background: transparent;
}
.timeline {
  width: calc(100% - (3em + 2em + 0.5em));
  margin-right: 0.5em;
}
.sound-button {
  background-color: transparent;
  border: 0;
  width: 2em;
  height: 2em;
  cursor: pointer;
  padding: 0;
}
</style>
