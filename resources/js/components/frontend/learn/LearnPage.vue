<template>
  <div class="wrapper h-screen flex flex-column">
    <div class="sticky inset-x-0 top-0 bg-white z-50">
      <main-header-component :user="user" :breadcrumb="breadcrumb"/>
    </div>
    <div v-show="!showResult" class="w-full h-full sm:overflow-hidden overflow-y-auto content content gap-4">
      <button @click="toggle()" :class="[open ? 'hidden' : 'block']"
        class="focus:outline-none transition-color duration-700 sidebarButton absolute">
        <span class="block transform origin-center font-bold">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.46967 17.5303C8.17678 17.2374 8.17678 16.7626 8.46967 16.4697L12.9393 12L8.46967 7.53033C8.17678 7.23744 8.17678 6.76256 8.46967 6.46967C8.76256 6.17678 9.23744 6.17678 9.53033 6.46967L14.5303 11.4697C14.671 11.6103 14.75 11.8011 14.75 12C14.75 12.1989 14.671 12.3897 14.5303 12.5303L9.53033 17.5303C9.23744 17.8232 8.76256 17.8232 8.46967 17.5303Z" fill="#2162FF"/>
          </svg>
        </span>
      </button>
      <div class="flex max-h-full sidebar z-10 border-r-2" :class="[!open ? 'hidden' : ' w-[350px] block']" >
        <!-- Sidebar -->
        <div class="flex w-full overflow-auto">

          <!-- Sidebar Content -->
            <div ref="content" class="bg-white  listLesson"
            :class="[open ? 'w-[350px]' : 'w-0 hidden']">
              <ListLesson :lessons="listLevel" :lessonType="lessonType" :levelName="levelName" v-model="open"
              :onGetLessonDetail="getLessonDetail" />
            <slot></slot>
          </div>
        </div>
      </div>
      <div :class="[`bg-white rounded overflow-x-auto lesson flex-grow mt-2 ${!open && 'ml-3'}`]" style="width: 50px;">
        <Lesson :content="lessonContent" :lessonType="lessonType"/>
      </div>
      <div class="w-[350px] rounded overflow-auto questions mt-2">
        <ListeningQuestions ref="listeningQuestions" :topics="lessonQuestions" v-if="this.lessonType == 'listening'" @update-content="updateContent"
          :onSubmit="submit" />
        <Questions :questions="lessonQuestions" :lessonType="lessonType" :onSubmit="submit" v-if="this.lessonType != 'listening'"/>
      </div>
    </div>
    <div v-show="showResult" class="flex justify-center">
        <div class="max-w-[736px] mt-5 bg-white min-h-fit p-4" style="width: 90%;">
          <div class="flex">
            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M21.928 8.15607L14.5678 4.78861C13.7768 4.427 13.1289 4.26126 12.4961 4.27633C11.8633 4.26126 11.2154 4.427 10.4244 4.78861L3.06417 8.15607C2.53683 8.38961 2.26562 8.82655 2.26562 9.27103C2.26562 9.72304 2.53683 10.1524 3.06417 10.386L5.97963 11.7043L10.8689 9.4217C10.8387 9.36897 10.8237 9.32376 10.8237 9.27103C10.8237 8.72109 11.6599 8.32934 12.5187 8.32934C13.385 8.32934 14.2137 8.72109 14.2137 9.27103C14.2137 9.83604 13.385 10.2353 12.5187 10.2353C12.255 10.2353 11.9838 10.1976 11.7427 10.1223L7.15485 12.2618L10.4244 13.761C11.2154 14.1226 11.8633 14.2808 12.4961 14.2733C13.1289 14.2808 13.7768 14.1226 14.5678 13.761L21.928 10.386C22.4554 10.1449 22.7266 9.72304 22.7266 9.27103C22.7266 8.82655 22.4554 8.38961 21.928 8.15607ZM7.12472 13.4672V17.8743C8.43555 18.6577 10.2963 19.1324 12.4961 19.1324C16.9182 19.1324 19.9542 17.2264 19.9542 14.8759V12.5029L15.0273 14.7629C14.1158 15.1773 13.2871 15.3882 12.4961 15.3807C11.7126 15.3882 10.8764 15.1773 9.96484 14.7629L7.12472 13.4672ZM5.03795 12.5104V14.8759C5.03795 15.5314 5.38449 16.3073 6.00977 16.9702V12.9549L5.03795 12.5104ZM5.34682 19.6597V21.6109C5.34682 22.2512 5.76869 22.6731 6.40904 22.6731H6.72545C7.36579 22.6731 7.78013 22.2512 7.78013 21.6109V19.6597C7.78013 19.1625 7.53153 18.8009 7.12472 18.6577V17.8743C6.68778 17.6106 6.3111 17.3093 6.00977 16.9702V18.6577C5.60296 18.7934 5.34682 19.1625 5.34682 19.6597Z" fill="#2162FF"/>
            </svg>
            <span>{{ selectedLessonName || ''  }}</span>
          </div>
          <div class="text-5xl text-center w-100  mt-5 font-bold">
            {{ formatResultTime(Date.now() - this.begin)  || 0 }}
          </div>
          <div class="text-xl text-center w-100 mt-2">
            {{ new Date().toLocaleDateString()  }}
          </div>
          <div class="flex justify-center mt-5">
            <div class="border-2 px-5 py-3 rounded">
              <p class="text-center text-[14px]">Score</p>
              <p  class="text-center text-[14px] rounded mt-2 px-2 font-bold" style="background-color: #E6E8EC;">{{ scores }}</p>
            </div>
          </div>
          <div v-if="this.lessonType != 'listening'" class="mt-3">
            <table class="table">
              <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Result</th>
                <th scope="col">Your Answer</th>
                <th scope="col">Correct Answer</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="(item, index) in lessonQuestions">
                <th scope="row">{{ index + 1 }}</th>
                <td>{{ getStatus(item) }}</td>
                <td>{{ getYourAnswer(item) }}</td>
                <td>{{ getCorrectAnswer(item) }}</td>
              </tr>
              </tbody>
            </table>
          </div>
          <div v-if="this.lessonType == 'listening'" class="mt-3">
            <table class="table">
              <thead>
              <tr>
                <th scope="col">Topic</th>
                <th scope="col">No</th>
                <th scope="col">Result</th>
                <th scope="col">Your Answer</th>
                <th scope="col">Correct Answer</th>
              </tr>
              </thead>
              <tbody>
              <tr v-for="item in listeningQuestions">
                <th scope="row">{{ Number(item.topic_index) + 1 }}</th>
                <td>{{ Number(item.question_index) + 1 }}</td>
                <td>{{ getStatus(item) }}</td>
                <td>{{ getYourListeningAnswer(item) }}</td>
                <td>{{ getCorrectListeningAnswer(item) }}</td>
              </tr>
              </tbody>
            </table>
          </div>
          <div class="flex justify-center mt-5 ">
            <a href="#" @click="handleRedirect" class="flex py-2 cursor-pointer justify-center" style="width: 50%">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5303 7.46967C10.8232 7.76256 10.8232 8.23744 10.5303 8.53033L7.06066 12L10.5303 15.4697C10.8232 15.7626 10.8232 16.2374 10.5303 16.5303C10.2374 16.8232 9.76256 16.8232 9.46967 16.5303L5.46967 12.5303C5.17678 12.2374 5.17678 11.7626 5.46967 11.4697L9.46967 7.46967C9.76256 7.17678 10.2374 7.17678 10.5303 7.46967Z" fill="#141416"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 12C5.25 11.5858 5.58579 11.25 6 11.25L18 11.25C18.4142 11.25 18.75 11.5858 18.75 12C18.75 12.4142 18.4142 12.75 18 12.75L6 12.75C5.58579 12.75 5.25 12.4142 5.25 12Z" fill="#141416"/>
              </svg>
              <span class="ml-2">
                Quay lại
              </span>
            </a>
            <a  href="/history" :class="[`flex bg-${lessonType} py-2 rounded cursor-pointer justify-center` ]" style="width: 50%">
              <span class="text-white mr-2">
                History
              </span>
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M13.4697 16.5303C13.1768 16.2374 13.1768 15.7626 13.4697 15.4697L16.9393 12L13.4697 8.53033C13.1768 8.23744 13.1768 7.76256 13.4697 7.46967C13.7626 7.17678 14.2374 7.17678 14.5303 7.46967L18.5303 11.4697C18.8232 11.7626 18.8232 12.2374 18.5303 12.5303L14.5303 16.5303C14.2374 16.8232 13.7626 16.8232 13.4697 16.5303Z" fill="white"/>
                <path fill-rule="evenodd" clip-rule="evenodd" d="M18.75 12C18.75 12.4142 18.4142 12.75 18 12.75L6 12.75C5.58579 12.75 5.25 12.4142 5.25 12C5.25 11.5858 5.58579 11.25 6 11.25L18 11.25C18.4142 11.25 18.75 11.5858 18.75 12Z" fill="white"/>
              </svg>

            </a>
          </div>
        </div>
    </div>
  </div>
</template>
<script>
import baseRequest from "../../../utils/baseRequest";
import "./learn.css";
import ListLesson from "./ListLesson.vue";
import Lesson from './Lesson.vue';
import Questions from './Questions.vue';
import ListeningQuestions from './ListeningQuestions.vue';
import ExamIcon from '../../../../../public/images/header/feature.svg';
export default {
  props: ["user", "query"],
  components: {
    ListLesson,
    Lesson,
    Questions,
    ListeningQuestions
  },

  data() {
    return {
      showResult: false,
      level: 1,
      show: false,
      listLevel: [],
      listLesson: [],
      levelCountPassed: 1,
      examResult: [],
      keyUrl: "",
      levelId: null,
      dataHistory: [],
      targetScore: 20,
      open: true,
      dimmer: true,
      right: false,
      lessonType: '',
      lessonContent: '',
      lessonQuestions: null,
      levelName: '',
      scores: '',
      begin: null,
      breadcrumb: [
        { label: 'Study', icon: ExamIcon },
      ],
      selectedLessonName: '',
      results: [],
      selectedAnswers: {},
      inputAnswerValues: {},
      listeningQuestions: [],
    };
  },
  watch: {
    "open" : {
        handler(value) {
            localStorage.setItem('section-list-show', value ? 1 : 0)
        }
    }
    },
  methods: {
    handleRedirect() {
      window.location.href = window.location.href;
    },
    async getAudioDetail(audioId) {
      try {
        let rs = await baseRequest.get(`/admin/get-detail-audio-question-listening/${audioId}`);
        if (rs.data.status == 200) {
          const data = rs.data.data;
          if (data) {
            return data?.question_listening;
          }
          return null;
        }
        return null;
      } catch (e) {
      } finally {
      }
    },
    getYourAnswer(item) {
      const id = item.id;
      const answers = item.answers;
      if (item.type === 1) {
        const answerId = this.selectedAnswers[id];
        const answer = answers.find((el) => {
          return el.answer_id === answerId;
        });
        return answer?.text;
      } else {
        return this.inputAnswerValues[id] ?? '';
      }
    },
    getCorrectAnswer(item) {
      if(item.answers?.length === 1) {
        return item.answers[0].text
      } else {
        const answerId = item.right_answers.answer_id;
        const answer = item.answers.find((el) => {
          return el.answer_id === answerId;
        });

        return answer.text;
      }
    },
    getCorrectListeningAnswer(item) {
      if(item.answer_listening?.length === 1) {
        return item.answer_listening[0].text
      } else {
        const answerId = item.right_answers.answer_id;
        const answer = item.answer_listening.find((el) => {
          return el.answer_id === answerId;
        });

        return answer.text;
      }
    },
    getYourListeningAnswer(item) {
      const id = item.id;
      const answers = item.answer_listening;
      if (item.type === 1) {
        const answerId = this.selectedAnswers[id];
        const answer = answers.find((el) => {
          return el.answer_id === answerId;
        });
        return answer?.text;
      } else {
        return this.inputAnswerValues[id] ?? '';
      }
    },
    getStatus(item) {
      const result = this.results[item.id] ?? [];
      let keys = Object.keys(result);
      if (keys.length > 0) {
        let firstKey = keys[0];
        let status = result[firstKey];
        console.log(status);
        return status ? 'Right Answer' : 'Wrong Answer';
      }
      return 'Wrong Answer';
    },
    formatResultTime(mili) {
      return $Helper.millisToMinutesAndSeconds(mili);
    },
    toggle() {
      this.open = !this.open;
    },
    async getDetailLevel() {
      const loading = this.$loading({
        lock: true,
        text: "Loading",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)",
      });
      try {
        let rs = await baseRequest.post(`/admin/detail-level`, { id: this.levelId });
        if (rs.data.status == 200) {
          const data = {
            grammar: rs.data.data?.grammar,
            writing: rs.data.data?.learn,
            listening: rs.data.data?.listen,
            pronunciation: rs.data.data?.pronunciation,
            reading: rs.data.data?.reading,
            speaking: rs.data.data?.speak,
            vocabulary: rs.data.data?.vocabulary,
          };

          if (data) {
            this.listLevel = data[this.lessonType];
          }
        }
      } catch (e) {
        console.log(e);
      } finally {
        loading.close();
      }
    },
    async getLessonDetail(lessonId, lessonName) {
      if(this.selectedLessonId === lessonId) return
      this.selectedLessonId = lessonId;
      this.selectedLessonName = lessonName;
      const loading = this.$loading({
        lock: true,
        text: "Loading",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)",
      });
      try {
        let rs = await baseRequest.get(`/admin/detail-topic-${this.lessonType}/${this.selectedLessonId}`);
        if (rs.data.status == 200) {
          if (this.lessonType == 'listening') {
            this.lessonContent = rs.data.data;
            this.lessonQuestions = rs.data.data['topic_audio_listen']
          } else if (this.lessonType == 'pronunciation') {
            rs.data.data[`question_${this.lessonType}`].forEach(function(item) {
                item.answers = item.answer_pronunciation;
                delete item.answer_pronunciation;
                item.right_answers = item.right_answer_pronunciation;
                delete item.right_answer_pronunciation;
            });
            this.lessonContent = rs.data.data;
            this.lessonQuestions = rs.data.data[`question_${this.lessonType}`];
          } else if (this.lessonType == 'reading') {
            rs.data.data[`question_${this.lessonType}`].forEach(function(item) {
                item.answers = item.answer_reading;
                delete item.answer_reading;
                item.right_answers = item.right_answer_reading;
                delete item.right_answer_reading;
            });
            this.lessonContent = rs.data.data;
            this.lessonQuestions = rs.data.data[`question_${this.lessonType}`];
          } else if (this.lessonType == 'writing') {
            rs.data.data['question_lesson'].forEach(function(item) {
                item.answers = item.answer_lesson;
                delete item.answer_lesson;
                item.right_answers = item.right_answer_lesson;
                delete item.right_answer_lesson;
            });
            this.lessonContent = rs.data.data;
            this.lessonQuestions = rs.data.data['question_lesson'];
          } else {
            this.lessonContent = rs.data.data;
            this.lessonQuestions = rs.data.data[`question_${this.lessonType}`];
          }
        }
      } catch (e) {
        console.log(e);
      } finally {
        loading.close();
      }
    },
    async submit(correctAnswers, questionCount, results, selectedAnswers, inputAnswerValues) {
      this.results = results;
      this.selectedAnswers = selectedAnswers;
      this.inputAnswerValues = inputAnswerValues;
      console.log(this.inputAnswerValues);
      this.scores = `${correctAnswers}/${questionCount}`;
      const requestParams = {
        test_type: this.query.skill ?? '',
        topic_name: this.selectedLessonName,
        scores: `${correctAnswers}/${questionCount}`,
        level_id: this.levelId,
        no_exam: true,
        completion_time: (Date.now() - this.begin) || 0
      }
      const loading = this.$loading({
        lock: true,
        text: "Loading",
        spinner: "el-icon-loading",
        background: "rgba(0, 0, 0, 0.7)",
      });
      try {
        let rs = await baseRequest.post(`/admin/save-history`, requestParams);
        this.showResult = true
      } catch (e) {
        console.log(e);
      } finally {
        loading.close();
      }
    },
    updateContent(audioId) {
      let newData = this.lessonQuestions.find(item => item.id === audioId);
      this.lessonContent.description = newData.content;
    },
    async getListeningQuestions() {
      let questions = [];
      if (this.lessonType === 'listening') {
        for (let [index, item] of Object.entries(this.lessonQuestions)) {
          try {
            let rs = await baseRequest.get(`/admin/get-detail-audio-question-listening/${item.id}`);
            if (rs.data.status == 200) {
              const data = rs.data.data;
              if (data) {
                let listenQuestions = data?.question_listening;
                for (let i = 0; i < listenQuestions.length; i++) {
                  listenQuestions[i]['topic_index'] = index;
                  listenQuestions[i]['question_index'] = i;
                }
                questions = questions.concat(listenQuestions);
              }
            }
          } catch (e) {
          } finally {
          }
        }
      }
      this.listeningQuestions = questions;
    }
  },
  async created() {
    const { skill, levelId, levelName } = this.query;
    this.lessonType = skill ? skill.toLowerCase() : '';
    this.levelId = levelId;
    this.levelName = levelName;
    this.breadcrumb = [... this.breadcrumb, {
      label: levelName
    }, {
      label: skill
    }]
    this.begin = Date.now();
    await this.getDetailLevel();
    await this.getLessonDetail(this.listLevel[0]?.id, this.listLevel[0]?.name);
    await this.getListeningQuestions();
  },
  async mounted() {
    let x = await localStorage.getItem('section-list-show')
    this.open = Number(x)
  },
  computed: {
  }
};
</script>
<style scoped>
@media only screen and (min-width: 1280px) {
  .content {
    display: flex;
  }

  .sidebar {}

  .lesson {
    flex-grow: 1;
  }

  .questions {
    width: 350px;
  }

  .sidebarButton {
    border-radius: 0px 0px 24px 0px;
    border-right: 2px solid var(--color-base-200, #F4F5F6);
    background: var(--color-white-100, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(0, 0, 0, 0.15);
    padding: 0.75rem;
    height: fit-content;
  }

  .main {
    width: 100%;
    display: flex;
  }
}

@media only screen and (max-width: 1279px) {
  .content {
    display: flex;
  }

  .sidebarButton {
    border-radius: 0px 0px 24px 0px;
    border-right: 2px solid var(--color-base-200, #F4F5F6);
    background: var(--color-white-100, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(0, 0, 0, 0.15);
    height: 96px;
  }

  .main {
    width: 100%;
    display: flex;
    flex-direction: row;
    margin: 12px;
  }

  .sidebar {
    height: 100%;
    position: absolute;
  }

  .lesson {
    margin-right: 12px;
  }
}

@media only screen and (max-width: 900px) {
  .content {
    display: block;
    margin-top: 2px;
    overflow: auto !important;;
  }

  .lesson {
    width: 100%!important;
    margin: 0!important;
    /* border-radius: unset !important; */
  }

  .questions {
    width: 100%;
    border-radius: unset !important;
  }

  .sidebar {
    height: 100%;
    position: absolute;
  }

  .sidebarButton {
    border-radius: 0px 0px 24px 0px;
    border-right: 2px solid var(--color-base-200, #F4F5F6);
    background: var(--color-white-100, #FFF);
    box-shadow: 0px 4px 20px 0px rgba(0, 0, 0, 0.15);
    height: 96px;
  }

  .main {
    display: block;
  }
}</style>
