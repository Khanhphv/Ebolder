<template>
  <div class="w-full">
    <header-component :user="user" @openDialog="openDialog" />
    <div class="w-full max-w-[1206px] mx-auto p-4">
      <div
        class="bg-blur-f lg:px-[48px] lg:py-[48px] px-[16px] py-[16px]"
        v-show="isShowLabel"
      >
        <h2 class="text-[28px] font-semibold leading-[120%] text-center mb-4">
          <p>GRAMMAR </p>
        </h2>
        <h2 class="text-[24px] font-semibold leading-[120%] text-center mt-4">
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
        <div v-html="data.description"></div>
      </div>
      <a
        href="/learn"
        style="text-decoration: none"
        v-show="!isShowLabel && !request.exam"
      >
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
            <p>
              You have reached {{ arrRightAns.length }} of
              {{ questions.length }} point(s),
            </p>
          </h2>
          <div class="flex items-center justify-center">
            <el-progress
              type="circle"
              :percentage="
                Number(
                  ((arrRightAns.length / questions.length) * 100).toFixed(2)
                )
              "
            ></el-progress>
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

      <div
        v-if="isShowLabel"
        class="w-full mt-4"
        v-for="(question, index) in questions"
        :key="question.id"
      >
        <p class="text-[16px]">
          Question <strong>{{ index + 1 }}</strong> of
          <strong>{{ questions.length }}</strong>
        </p>
        <p class="mt-2 mb-4 text-[16px]" v-if="question.type == 1">
          {{ question.question }}
        </p>
        <div
          class="grid grid-cols-1 md:grid-cols-2 gap-4"
          v-if="question.type == 1"
        >
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
              v-model="answerData[index].radioValue"
              :value="item.id"
              hidden
              :disabled="isShowLabel == false"
            />
            <label
              :for="`test${item.id}`"
              class="flex justify-between items-center w-full py-2 px-4 border-2 rounded-lg border-answer"
              :class="[answerData[index].radioValue == item.id ? `active` : '']"
            >
              <div
                class="text-[16px] font-semibold"
                :style="
                  answerData[index].radioValue == item.id
                    ? `color: #5B5EA6`
                    : 'color: #eee'
                "
              >
                {{ item.text }}
              </div>
              <div>
                <AVue
                  :isActive="
                    answerData[index].radioValue == item.id ? true : false
                  "
                  v-if="indexItem == 0"
                />
                <BVue
                  :isActive="
                    answerData[index].radioValue == item.id ? true : false
                  "
                  v-if="indexItem == 1"
                />
                <CVue
                  :isActive="
                    answerData[index].radioValue == item.id ? true : false
                  "
                  v-if="indexItem == 2"
                />
                <DVue
                  :isActive="
                    answerData[index].radioValue == item.id ? true : false
                  "
                  v-if="indexItem == 3"
                />
              </div>
            </label>
          </div>
        </div>
        <div
          class="flex mt-2 mb-4 text-[16px] mt-4 items-center"
          v-if="question.type == 2"
        >
          <div
            v-for="(item, indexAns) in arrQuestion(question)"
            :key="indexAns"
          >
            <input
              type="text"
              class="mx-2 text-black border-none outline-none rounded-md w-[86px] lg:w-[150px] px-2 py-1"
              v-model="
                answerData[index].dataChoose[getIndexSharp(question, indexAns)]
                  .radioValue
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
        v-for="(question, index) in questions"
        :key="question.id"
      >
        <p class="text-[16px]">
          Question <strong>{{ index + 1 }}</strong> of
          <strong>{{ questions.length }}</strong>
        </p>
        <p class="mt-2 mb-4 text-[16px]" v-if="question.type == 1">
          {{ question.question }}
        </p>
        <div
          class="grid grid-cols-1 md:grid-cols-2 gap-4"
          v-if="question.type == 1"
        >
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
              v-model="answerData[index].radioValue"
              :value="item.id"
              hidden
              :disabled="isShowLabel == false"
            />
            <label
              :for="`test${item.id}`"
              class="flex justify-between items-center w-full py-2 px-4 border-2 rounded-lg border-answer min-h-[70px]"
              :class="[
                answerData[index].radioValue == item.id ? `active` : '',
                answerData[index].radioValue == answerData[index].right_answer
                  ? `right`
                  : 'wrong',
                item.id == answerData[index].right_answer ? `right_wait` : '',
                item.id == answerData[index].right_answer &&
                item.id == answerData[index].radioValue
                  ? 'bgright'
                  : '',
              ]"
            >
              <div
                class="text-[16px] font-semibold text-answer"
                :class="[
                  answerData[index].radioValue == item.id ? `active` : '',
                  answerData[index].radioValue == answerData[index].right_answer
                    ? `right`
                    : 'wrong',
                  item.id == answerData[index].right_answer ? `right_wait` : '',
                  item.id == answerData[index].right_answer &&
                  item.id == answerData[index].radioValue
                    ? 'bgright'
                    : '',
                ]"
              >
                {{ item.text }}
              </div>
              <div>
                <el-button
                  v-if="item.id == answerData[index].right_answer"
                  type="success"
                  icon="el-icon-check"
                  circle
                  size="mini"
                ></el-button>
                <div v-else>
                  <AVue
                    :isActive="
                      answerData[index].radioValue == item.id ? true : false
                    "
                    v-if="indexItem == 0"
                  />
                  <BVue
                    :isActive="
                      answerData[index].radioValue == item.id ? true : false
                    "
                    v-if="indexItem == 1"
                  />
                  <CVue
                    :isActive="
                      answerData[index].radioValue == item.id ? true : false
                    "
                    v-if="indexItem == 2"
                  />
                  <DVue
                    :isActive="
                      answerData[index].radioValue == item.id ? true : false
                    "
                    v-if="indexItem == 3"
                  />
                </div>
              </div>
            </label>
          </div>
        </div>
        <div
          class="flex mt-2 mb-4 text-[16px] mt-4 items-center"
          v-if="question.type == 2"
        >
          <div
            v-for="(item, indexAns) in arrQuestion(question)"
            :key="indexAns"
          >
            <input
              type="text"
              class="mx-2 text-white border-none outline-none rounded-md w-[86px] lg:w-[150px] px-2 py-1"
              v-model="
                answerData[index].dataChoose[getIndexSharp(question, indexAns)]
                  .radioValue
              "
              :class="[
                answerData[index].dataChoose[getIndexSharp(question, indexAns)]
                  .radioValue?.trim()?.toLowerCase() ==
                answerData[index].dataRight[getIndexSharp(question, indexAns)]
                  .right_answer?.trim()?.toLowerCase()
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
      <button
        v-show="isShowLabel && questions.length > 0"
        @click="submit"
        class="cursor-pointer px-4 py-2 text-center uppercase leading-[28px] flex items-center justify-center font-light rounded-md bg-button text-[19px] text-white hover:opacity-80 mt-4 ml-auto"
      >
        Finish
      </button>
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
      answerData: [],
      questions: [],
      arrRightAns: [],
      arrWrongAns: [],
      total: 0,
      isShowLabel: true,
      timeWork: 45 * 60 * 1000,
      timerun: 0,
      dialogVisible: false,
    };
  },
  components: {
    AVue,
    BVue,
    CVue,
    DVue,
    VueCountdown,
  },
  methods: {
    openDialog() {
      this.dialogVisible = true;
    },
    async submit() {
      this.$refs.countdown.abort();
      this.questions.forEach((item, index) => {
        this.answerData[index].right_answer = item.right_answer;
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
        test_type: "Grammar",
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
          let result = await baseRequest.post("/admin/save-history", dataHistory);
        } catch (e) {}
      }else {
        dataHistory.no_exam = true;
        try {
          let result = await baseRequest.post("/admin/save-history", dataHistory);
        } catch (e) {}
      }
      if (this.request.exam) {
        try {
          let result = await baseRequest.post(
            "/admin/save-exam-history-final",
            {
              id: this.request.historyId,
              result_grammar: `${this.arrRightAns.length}/${this.answerData.length}`,
              time: this.timerun,
            }
          );
          if (result.data.status === 200) {
            window.location.href = `${$Api.baseUrl}/english-level-test/Listening?testId=${this.request.l}&v=${this.request.v}&g=${this.request.g}&l=${this.request.l}&s=${this.request.s}&r=${this.request.r}&w=${this.request.w}&p=${this.request.p}&historyId=${this.request.historyId}&examId=${this.request.examId}&exam=true`;
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
                                  this.answerData[index].dataChoose[sum - 1]
                                    .radioValue
                                }'>`;
        }
      }
      return arrQuestion.join(" ");
    },
    async checkHistoryExam() {
      try {
        let config = {
          type: "Grammar",
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
    this.questions = this.data.question_grammar.map((grammar) => ({
      id: grammar.id,
      question: grammar.question,
      type: grammar.type,
      dataAns: grammar.answers.map((item) => ({
        id: item.answer_id,
        text: item.text,
      })),
      right_answer: grammar.right_answers
        ? grammar.right_answers.answer_id
        : "",
    }));
    this.questions.forEach((item) => {
      if (item.type == 1) {
        this.answerData.push({
          radioValue: null,
          right_answer: "",
          type: 1,
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
        });
      }
    });
    if (this.request.exam) {
      this.checkHistoryExam();
    }
  },
};
</script>
<style scoped>
.border-answer {
  border-color: #999;
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
  color: #999;
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
