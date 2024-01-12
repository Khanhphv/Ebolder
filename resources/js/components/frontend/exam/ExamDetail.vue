<template>
    <div class="wrapper h-screen flex flex-column">
        <div class="sticky inset-x-0 top-0 bg-white z-50">
            <main-header-component 
                :user="user" 
                :breadcrumb="breadcrumb" 
                :showTime="true"
                :onFinish="submit"
                v-model="skill"
                @handleExam="handleSelectedSkill"
            />
        </div>
        <div v-show="!showResult" class="flex flex-row gap-4  w-full h-full sm:overflow-hidden overflow-y-auto content">
            <button @click="toggle()" :class="[open ? 'hidden' : 'block']"
                class="focus:outline-none transition-color duration-700 sidebarButton absolute">
                <span class="block transform origin-center font-bold">
                    <img src="/images/learn/right.svg" alt="" />
                </span>
            </button>
            <div class="flex max-h-full sidebar z-10 border-r-2" :class="[!open ? 'hidden' : 'max-w-[350px] block']" style="width: 90%;">
                <!-- Sidebar -->
                <div class="flex w-full">
                    <!-- Sidebar Content -->
                    <div ref="content" class="bg-white listLesson" :class="[open ? 'w-[350px]' : 'hidden w-0']">
                        <ExamDetailSkills v-model="open" ref="examDetailSkills" />
                        <slot></slot>
                    </div>
                </div>
            </div>
            <div :class="[`bg-white rounded overflow-x-auto lesson flex-grow mt-2 ${!open && 'ml-3'}`]" style="width: 50px;">
                <ExamDetailContent :content="examBySkill" />
            </div>
            <div class="w-[350px] rounded overflow-y-auto  mt-2 questions">
                <ExamDetailListeningQuestions 
                    :topics="questions"
                    v-if="this.skill == 'listening'" 
                    :skill="skill" 
                    @update-content="updateContent"
                    @update-result="updateResult"
                    @update-selected-answer="updateSelectedAnswer"
                    @update-input-answer="updateInputAnswer"
                    />
                <ExamDetailQuestions
                    :questions="questions"
                    :skill="skill"
                    v-if="this.skill != 'listening'"
                    @update-result="updateResult"
                    @update-selected-answer="updateSelectedAnswer"
                    @update-input-answer="updateInputAnswer"
                />
            </div>
        </div>
        <div v-show="showResult" class="flex justify-center">
            <div class="max-w-[736px] mt-5 bg-white min-h-fit p-4" style="width: 90%;">
                <div class="flex items-center">
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M21.928 8.15607L14.5678 4.78861C13.7768 4.427 13.1289 4.26126 12.4961 4.27633C11.8633 4.26126 11.2154 4.427 10.4244 4.78861L3.06417 8.15607C2.53683 8.38961 2.26562 8.82655 2.26562 9.27103C2.26562 9.72304 2.53683 10.1524 3.06417 10.386L5.97963 11.7043L10.8689 9.4217C10.8387 9.36897 10.8237 9.32376 10.8237 9.27103C10.8237 8.72109 11.6599 8.32934 12.5187 8.32934C13.385 8.32934 14.2137 8.72109 14.2137 9.27103C14.2137 9.83604 13.385 10.2353 12.5187 10.2353C12.255 10.2353 11.9838 10.1976 11.7427 10.1223L7.15485 12.2618L10.4244 13.761C11.2154 14.1226 11.8633 14.2808 12.4961 14.2733C13.1289 14.2808 13.7768 14.1226 14.5678 13.761L21.928 10.386C22.4554 10.1449 22.7266 9.72304 22.7266 9.27103C22.7266 8.82655 22.4554 8.38961 21.928 8.15607ZM7.12472 13.4672V17.8743C8.43555 18.6577 10.2963 19.1324 12.4961 19.1324C16.9182 19.1324 19.9542 17.2264 19.9542 14.8759V12.5029L15.0273 14.7629C14.1158 15.1773 13.2871 15.3882 12.4961 15.3807C11.7126 15.3882 10.8764 15.1773 9.96484 14.7629L7.12472 13.4672ZM5.03795 12.5104V14.8759C5.03795 15.5314 5.38449 16.3073 6.00977 16.9702V12.9549L5.03795 12.5104ZM5.34682 19.6597V21.6109C5.34682 22.2512 5.76869 22.6731 6.40904 22.6731H6.72545C7.36579 22.6731 7.78013 22.2512 7.78013 21.6109V19.6597C7.78013 19.1625 7.53153 18.8009 7.12472 18.6577V17.8743C6.68778 17.6106 6.3111 17.3093 6.00977 16.9702V18.6577C5.60296 18.7934 5.34682 19.1625 5.34682 19.6597Z" fill="#2162FF"/>
                    </svg>
                    <span>
                        {{ examBySkill?.name }}
                    </span>
                </div>
                <div class="text-5xl text-center w-100  mt-5 font-bold">
                    {{ completion_time((Date.now() - this.begin)) || 0 }}
                </div>
                <div class="text-xl text-center w-100 mt-2">
                    {{ new Date().toLocaleDateString()  }}
                </div>
                <div class="grid justify-center mt-5 gap-4 grid-cols-1">
                    <div class="border-2 px-5 py-3 rounded">
                      <p class="text-center text-[14px]">Listening</p>
                      <p  class="text-center text-[14px] rounded mt-2 w-fit mx-auto px-2 font-bold" style="background-color: #E6E8EC;">{{ result?.result_listening }}</p>
                    </div>
                    <div class="mt-3">
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
                          <th scope="row">{{ Number(item.question_index) + 1 }}</th>
                          <td>{{ getStatus(item) }}</td>
                          <td>{{ getYourListeningAnswer(item) }}</td>
                          <td>{{ getCorrectListeningAnswer(item) }}</td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="border-2 px-5 py-3 rounded">
                    <p class="text-center text-[14px]">Speaking</p>
                    <p  class="text-center text-[14px] rounded mt-2 w-fit mx-auto px-2 font-bold" style="background-color: #E6E8EC;">{{ result?.result_speaking }}</p>
                    </div>
                    <div class="mt-3" v-if="speaking.data.length">
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
                        <tr v-for="(item, index) in speaking.data">
                          <th scope="row">{{ index + 1 }}</th>
                          <td>{{ getStatus(item) }}</td>
                          <td>{{ getYourAnswer(item) }}</td>
                          <td>{{ getCorrectAnswer(item) }}</td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="border-2 px-5 py-3 rounded">
                    <p class="text-center text-[14px]">Reading</p>
                    <p  class="text-center text-[14px] rounded mt-2 w-fit mx-auto px-2 font-bold" style="background-color: #E6E8EC;">{{ result?.result_reading }}</p>
                    </div>
                    <div class="mt-3">
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
                        <tr v-for="(item, index) in reading.data">
                          <th scope="row">{{ index + 1 }}</th>
                          <td>{{ getStatus(item) }}</td>
                          <td>{{ getYourAnswer(item) }}</td>
                          <td>{{ getCorrectAnswer(item) }}</td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="border-2 px-5 py-3 rounded">
                    <p class="text-center text-[14px]">Writing</p>
                    <p  class="text-center text-[14px] rounded mt-2 w-fit mx-auto px-2 font-bold" style="background-color: #E6E8EC;">{{ result?.result_writing }}</p>
                    </div>
                    <div class="mt-3">
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
                        <tr v-for="(item, index) in writing.data">
                          <th scope="row">{{ index + 1 }}</th>
                          <td>{{ getStatus(item) }}</td>
                          <td>{{ getYourAnswer(item) }}</td>
                          <td>{{ getCorrectAnswer(item) }}</td>
                        </tr>
                        </tbody>
                      </table>
                    </div>
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
                    <a  href="/history" :class="[`flex bg-[#2162FF] py-2 rounded cursor-pointer justify-center` ]" style="width: 50%">
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
import ExamDetailContent from "./ExamDetailContent.vue";
import ExamDetailQuestions from './ExamDetailQuestions.vue';
import ExamDetailListeningQuestions from './ExamDetailListeningQuestions.vue';
import ExamDetailSkills from "./ExamDetailSkills.vue";
import ExamIcon from '../../../../../public/images/header/exam.svg';
import VueCountdown from "@chenfengyuan/vue-countdown";

export default {
    props: ["param", "user"],
    components: {
        ExamDetailContent,
        ExamDetailQuestions,
        ExamDetailSkills,
        ExamDetailListeningQuestions,
        VueCountdown
    },
    data() {
        return {
            showResult: false,
            open: null,
            lessonContent: null,
            lessonQuestions: null,
            data: null,
            examBySkill: null,
            questions: null,
            skill: null,
            breadcrumb: [
                { label: 'Test', icon: ExamIcon },
            ],
            timeWork: 45 * 60 * 1000,
            timerun: 0,
            result: null,
            content: null,
            reading: {},
            readingQuestionCount: 0,
            speaking: {},
            speakingQuestionCout: 0,
            writing: {},
            writingQuestionCount: 0,
            listening: {},
            listeningQuestionCount: 0,
            begin: null,
            results: {},
            selectedAnswers: {},
            inputAnswerValues: {},
            listeningQuestions: [],
        }
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
            return item.answers[0]?.text
          } else {
            const answerId = item.right_answers?.answer_id;
            const answer = item.answers.find((el) => {
              return el.answer_id === answerId;
            });

            return answer?.text;
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
          const status = this.results[item.id] ?? false;
          console.log(status);

          return status ? 'Right Answer' : 'Wrong Answer';
        },
        async getListeningQuestions() {
          let questions = [];
          for (let [index, item] of Object.entries(this.listening.data)) {
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
          this.listeningQuestions = questions;
        },
        updateResult(questionId, answer) {
          console.log('ddd');
          this.results[questionId] = answer;
        },
        updateSelectedAnswer(questionId, answerId) {
          this.selectedAnswers[questionId] = answerId;
        },
        updateInputAnswer(questionId, text) {
          this.inputAnswerValues[questionId] = text;
        },
        completion_time(mili) {
            return $Helper.millisToMinutesAndSeconds(mili);
        },
        toggle() {
            this.open = !this.open;
        },
        shuffle (array) {
            return array.sort(() => Math.random() - 0.5); 
        },
        async getExamDetail() {
            try {
                let rs = await baseRequest.get(`/admin/get-detail-exam/${this.param}`);
                if (rs.data.status == 200) {
                    const data = rs.data.data;
                    this.breadcrumb = this.getBreadcrumb(data.name);

                    if (data) {
                        this.data = {
                            reading: data?.reading_id,
                            speaking: data?.speaking_id,
                            writing: data?.writing_id,
                            listening: data?.listening_id,
                        }
                    }
                }
            } catch (e) {
                console.log(e);
                alert('Error')
            } 
        },
        async getReadingExam() {
            try {
                let rs = await baseRequest.get(`/admin/detail-topic-reading/${this.data['reading']}`);
                if (rs.data.status == 200) {
                    const data = rs.data.data;
                    if(data) {
                        const formatedData = {
                            name: data.name,
                            description: data.description || data.content,
                            data: data.question_reading,
                        };
                        formatedData.data && formatedData.data.forEach(question => {
                            question.answers = question.type == 1 ? this.shuffle(question.answer_reading) : question.answer_reading;
                            delete question.answer_reading;
                            question.right_answers = question.right_answer_reading;
                            delete question.right_answer_reading;
                        })
                        this.reading = formatedData
                        this.readingQuestionCount = this.getQuestionCount(this.reading.data)
                    }
                }
            } catch (e) {
                console.log(e);
                alert('Error')
            }
        },
        async getListeningExam() {
            try {
                let rs = await baseRequest.get(`/admin/detail-topic-listening/${this.data['listening']}`);
                if (rs.data.status == 200) {
                    const data = rs.data.data;
                    if(data) {
                        const formatedData = {
                            name: data.name,
                            description: data.description || data.content,
                            data: data.topic_audio_listen,
                        };
                        this.listening = formatedData;
                        this.listeningQuestionCount = this.getQuestionCount(this.listening.data, 'listening')
                    }
                }
            } catch (e) {
                console.log(e);
                alert('Error')
            }
        },
        async getSpeakingExam() {
            try {
                let rs = await baseRequest.get(`/admin/detail-topic-speaking/${this.data['speaking']}`);
                if (rs.data.status == 200) {
                    const data = rs.data.data;
                    if(data) {
                        data.question_speak && data.question_speak.forEach(question => {
                            question.answers = question.type == 1 ? this.shuffle(question.answers) : question.answers;
                        })
                        const formatedData = {
                            name: data.name,
                            description: data.description || data.content,
                            data: data.question_speak,
                        };
                        this.speaking = formatedData;
                        this.speakingQuestionCout = this.getQuestionCount(this.speaking.data)
                    }
                }
            } catch (e) {
                console.log(e);
                alert('Error')
            }
        },
        async getWritingExam() {
            try {
                let rs = await baseRequest.get(`/admin/detail-topic-writing/${this.data['writing']}`);
                if (rs.data.status == 200) {
                    const data = rs.data.data;
                    if(data) {
                        const formatedData = {
                            name: data.name,
                            description: data.description || data.content,
                            data: data.question_lesson
                        };
                        formatedData.data && formatedData.data.forEach(question => {
                            question.answers = question.type ? this.shuffle(question.answer_lesson) : question.answer_lesson;
                            delete question.answer_lesson;
                            question.right_answers = question.right_answer_lesson;
                            delete question.right_answer_lesson;
                        })
                        this.writing = formatedData
                        this.writingQuestionCount = this.getQuestionCount(this.writing.data)
                    }
                }
            } catch (e) {
                console.log(e);
                alert('Error')
            }
        },
        handleSelectedSkill(skill) {
            if (this.$refs.examDetailSkills) {
                this.$refs.examDetailSkills.getExamDetail(skill);
            }
            this.skill = skill.toLowerCase();
            if(skill == 'reading') {
                this.questions = this.reading.data;
                this.examBySkill = {
                    name: this.reading.name,
                    description: this.reading.description,
                }
            } else if (skill == 'listening') {
                this.questions = this.listening.data;
                this.examBySkill = {
                    name: this.listening.name,
                    description: this.listening.description,
                }
            } else if (skill == 'speaking') {
                this.questions = this.speaking.data;
                this.examBySkill = {
                    name: this.speaking.name,
                    description: this.speaking.description,
                }
            } else if (skill == 'writing') {
                this.questions = this.writing.data;
                this.examBySkill = {
                    name: this.writing.name,
                    description: this.writing.description,
                }
            }
        },
        getQuestionCount(questions, type = '') {
            let count = 0;
            if (type) {
                questions && questions.forEach(question => {
                    count += question.question_listening.length;
                })
            } else {
            questions && questions.forEach(question => {
                    if(question.type == 2) {
                        count += question.answers.length;
                    } else {
                        count += 1
                    }
                })
            }
            return count;
        },
        getBreadcrumb(label) {
            return [
                ...this.breadcrumb, { label: label }
            ]
        },
        async submit() {
            
            const result_reading = localStorage.getItem('result_reading')
            const result_listening = localStorage.getItem('result_listening')
            const result_speaking = localStorage.getItem('result_speaking')
            const result_writing = localStorage.getItem('result_writing')
            
            const requestHistoryFinalParams = {
                exam_id: this.param,
                result_reading: result_reading || `0/${this.readingQuestionCount}`,
                result_speaking: result_speaking || `0/${this.speakingQuestionCout}`,
                result_listening: result_listening || `0/${this.listeningQuestionCount}`,
                result_writing: result_writing || `0/${this.writingQuestionCount}`,
                user_id: this.user.id,
                time: Date.now() - this.begin || 0,
                status: 'create',
            }
            this.result = requestHistoryFinalParams;
            const loading = this.$loading({
                lock: true,
                text: "Loading",
                spinner: "el-icon-loading",
                background: "rgba(0, 0, 0, 0.7)",
            });
            try {
                const rs = await baseRequest.post(`/admin/save-exam-history-final`, requestHistoryFinalParams);
                this.showResult = true;
            } catch (e) {
                console.log(e);
                alert('Error')
            } finally {
                localStorage.removeItem('result_reading');
                localStorage.removeItem('result_listening');
                localStorage.removeItem('result_speaking');
                localStorage.removeItem('result_writing');
                loading.close();
            }
        },
        handleCountdownProgress(data) {
            this.timerun = this.timeWork - data.totalMilliseconds + 1000;
            if (this.timerun === this.timeWork) {
                this.finishExam();
            }
        },
        updateContent(audioId) {
            let newData = this.listening.data.find(item => item.id === audioId);
            this.examBySkill.description = newData.content;
        },
    },
    async created() {
        const loading = this.$loading({
            lock: true,
            text: "Loading",
            spinner: "el-icon-loading",
            background: "rgba(0, 0, 0, 0.7)",
        });
        this.begin = Date.now();
        await this.getExamDetail();
        await this.getListeningExam();
        await this.getListeningQuestions();
        Promise.all([
            this.getReadingExam(),
            this.getSpeakingExam(),
            this.getWritingExam(),
        ])
        this.handleSelectedSkill('listening')
        loading.close()
    },
    async mounted() {
        let x = await localStorage.getItem('section-list-show')
        this.open = Number(x)
        localStorage.removeItem('result_reading');
        localStorage.removeItem('result_listening');
        localStorage.removeItem('result_speaking');
        localStorage.removeItem('result_writing');
    }
}
</script>

<style scoped>
@media only screen and (min-width: 1280px) {
    .content {
        display: flex;
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

@media only screen and (max-width: 1279px) and (min-width: 901px) {
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

}

@media only screen and (max-width: 900px) {
    .content {
        display: block;
        margin-top: 2px;
        overflow: auto !important;;
    }
    .lesson {
        width: 100% !important;
        flex-grow: 1;
        margin-left: 0px!important;
        border-radius: unset !important;
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
}
</style>