<template>
    <div class="bg-white p-3 flex flex-column md:rounded min-h-full">
        <div class="font-bold text-xl mb-4"> Topics</div>
        <div class="flex flex-row flex-wrap mb-3 cursor-pointer gap-3">
            <div v-for="(item, index) in this.topics" :key="item.id"
                class="rounded-full w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center font-semibold text-sm"
                @click="handleSelectTopic(index)"
                :class="{ 'bg-[#2162FF] text-white': selectedTopicIndex == index, 'bg-[#E6E8EC]': selectedTopicIndex != index }">
                {{ index + 1 }}
            </div>
        </div>
        <div class="bg-blur-f mb-4">
            <audio ref="audio" v-show="selectedTopic?.file_type !== 'video' && selectedTopic?.audio" class="w-full bg-transparent" controls>
                <source :src="`${baseURl}/upload/audio/${this.selectedTopic?.audio}`"
                    type="audio/mpeg" />
            </audio>
            <video ref="video" v-show="selectedTopic?.file_type == 'video' && selectedTopic?.audio" class="w-full bg-transparent" controls>
                <source :src="`${baseURl}/upload/audio/${this.selectedTopic?.audio}`"
                    type="video/mp4" />
            </video>
            <!-- <div class="controls bg-[#E6E8EC] rounded px-3">
                <button class="player-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#FFF">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
                <input type="range" class="timeline" max="100" value="0" disabled />
                <button class="sound-button">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="#FFF">
                        <path fill-rule="evenodd"
                            d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div> -->
        </div>
        <div class="font-bold text-xl mb-4"> Questions</div>
        <div v-show="this.topics?.length > 0">
        <div class="flex flex-row flex-wrap mb-4 gap-3">
            <div v-for="(item, index) in this.questions" :key="item.id"
                class="rounded-full w-7 h-7 sm:w-10 sm:h-10 flex items-center justify-center font-semibold text-sm"
                @click="handleSelectQuestion(index)"
                :class="[ selectedIndex != index && questionDone[item.id] ? 'bg-[#35509A] text-white': (selectedIndex == index ? `bg-[#2162FF] text-white` : 'bg-[#E6E8EC]')]">
                {{ index + 1 }}
            </div>
        </div>
        <div class="py-4 border-t border-[#e6e8ec]">
            <p class="text-lg font-semibold mb-2">Question {{ selectedIndex + 1 }}</p>
            <p class="font-semibold"  id="question">
                <span v-for="(item, index) in handleQuestionWithInput(selectedQuestion)" :key="'div' + selectedQuestion?.id  + index">
                    <span v-if="item !== '#'" :key="'span' + index">{{ item }}</span>
                    <input v-else type="text"
                            @input="(event) => handleAnswerInput(event, selectedQuestion, index)"
                            v-model="inputAnswerValues[`${index}${selectedQuestion?.id}`]"
                            maxlength="255"
                            class="
                                mx-2
                                text
                                border
                                border-[#e6e8ec]
                                outline-none
                                rounded-md
                                w-[43px]
                                lg:w-[75px]
                                px-2
                                py-1
                            " 
                            :key="'input' + index"
                            :id="'input' + selectedQuestion?.id + index"
                            />
                </span>
            </p>
        </div>
        <div class="flex-grow" v-show="selectedQuestion?.type == 1">
            <div v-for="(answer, index) in selectedQuestion?.['answer_listening']" :key="index" class="rounded-[8px] border-2 p-3 mb-3"
                :class="{ 'border-[#2162FF]': isQuestionAnswered(answer?.id, selectedQuestion?.id)} "
                @click="handleSelectAnswer(answer?.id, selectedQuestion?.id)">
                <input type="radio" :id="`test${answer?.id}`" :value="answer?.id" :checked="isQuestionAnswered(answer?.id, selectedQuestion?.id)" />
                <label class="text-lg font-semibold ml-1">
                    {{ convertToCharacter(index) }}
                </label>
                <p class="font-light">{{ answer?.text }}</p>
            </div>
        </div>
        <div class="w-full flex flex-row mt-4">
            <div :class="{'button-active': selectedTopicIndex <= this.topics?.length - 1 && selectedTopicIndex > 0, 'button-inactive': selectedTopicIndex == 0 }"
                @click="handleSelectTopic(selectedTopicIndex - 1, 'back')">
                <Left :color="selectedTopicIndex <= this.topics?.length - 1 && selectedTopicIndex > 0 ? activeColor : inactiveColor"/>
                Back
            </div>
            <div class="w-3"></div>
            <div :class="{'button-active': selectedTopicIndex < this.topics?.length - 1, 'button-inactive': selectedTopicIndex >= this.topics?.length - 1}"
                @click="handleSelectTopic(selectedTopicIndex + 1, 'next')"
                >
                Next
                <Right :color="selectedTopicIndex < this.topics?.length - 1 ? activeColor : inactiveColor" />
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import baseRequest from "../../../utils/baseRequest";
import Right from '../../../svg/Right.vue';
import Left from '../../../svg/Left.vue';

export default {
    props: ["topics", "skill", "onSubmit"],
    components: {
        Right,
        Left
    },
    data() {
        return {
            selectedIndex: 0,
            selectedQuestion: this.questions?.[0],
            selectedTopicIndex: 0,
            selectedTopic: this.topics?.[0],
            selectedAnswerId: 0,
            selectedAnswers: {},
            questions: null,
            listQuestion: [],
            baseURl: $Api.baseUrl,
            questionDone: {},
            questionCount: 0,
            inputAnswersByQuestion: {},
            inputAnswers: {},
            inputAnswerValues: {},
            activeColor: '#2162ff',
            inactiveColor: '#141416',
            results: {},
            answerEachInputQuestion: {},
            arrowLeft: require('../../../../../public/images/learn/arrow-left.svg'),
            arrowRight: require('../../../../../public/images/learn/arrow-right.svg')
        }
    },
    methods: {
        handleSelectQuestion(index) {
            this.selectedIndex = index;
            this.selectedQuestion = this.questions?.[this.selectedIndex];
        },
        async handleSelectTopic(index, action) {
            if (action == 'back' && this.selectedTopicIndex <= 0 || action == 'next' && this.selectedTopicIndex >= this.topics?.length - 1) {
                return;
            }
            this.selectedTopicIndex = index;
            this.selectedTopic = this.topics?.[this.selectedTopicIndex];
            await this.getAudioDetail(this.topics?.[this.selectedTopicIndex]?.id);
        },
        convertToCharacter(number) {
            return String.fromCharCode(65 + number)
        },
        handleSelectAnswer(answerId, questionId) {
            this.selectedAnswerId = answerId;
            const rightAnswer = this.selectedQuestion.right_answers.answer_id;

            this.$emit('update-selected-answer', questionId, answerId);
            this.$set(this.results, questionId, {});
            
            if (answerId) {
                this.$set(this.selectedAnswers, questionId, answerId);
                if (answerId == rightAnswer) {
                    this.results[questionId][answerId] = true;
                    this.$emit('update-result', questionId, true);
                } else {
                    this.results[questionId][answerId] = false;
                    this.$emit('update-result', questionId, false);
                }
            }
            this.saveResult();
            this.questionDone[questionId] = true;
            this.isQuestionAnswered(answerId, questionId)
        },
        handleAnswerInput(event, question, elementIndex) {
            // get index of input
            const inputIndex = this.findCollectionIndex(document.getElementById('question').getElementsByTagName('input'), event.target.id)
            const answerId = question.answer_listening[inputIndex].answer_id;
            const rightAnswer = question.answer_listening[inputIndex].text.toLowerCase().trim();
            const inputText = event.target.value ? event.target.value.toLowerCase().trim() : '';
            const questionId = question.id;
            this.$emit('update-input-answer', questionId, inputText);
          // store value of each input
            this.$set(this.inputAnswerValues, `${elementIndex}${question.id}`, event.target.value)
            
            if (!this.results[questionId]) {
                this.$set(this.results, questionId, {});
            }
            
            if(rightAnswer == inputText) {
                this.results[questionId][answerId] = true;
                this.$emit('update-result', questionId, true);
            } else {
                this.results[questionId][answerId] = false;
                this.$emit('update-result', questionId, false);
            }

            if(event.target.value || event.target.value != '' ) {
                this.questionDone[question.id] = true;
            } else {
                this.questionDone[question.id] = false;
            }
            this.saveResult();
        },
        findCollectionIndex(collectionsArray, targetId) {
            for (let i = 0; i < collectionsArray.length; i++) {
                if (collectionsArray[i].id === targetId) {
                    return i;
                }
            }
            return -1;
        },
        handleQuestionWithInput(question) {
            const type = question?.type;
            const text = question?.question;
            if (!text?.includes("#") || type == 1) return [text];
            const executed = text.split(/(#)/)
            return executed;
        },
        isQuestionAnswered(answerId, questionId) {
            return this.selectedAnswers[questionId] == answerId;
        },
        shuffle (array) {
            return array.sort(() => Math.random() - 0.5); 
        },
        async getAudioDetail(audioId) {
            if (this.listQuestion.hasOwnProperty(audioId)) {
                this.questions = this.listQuestion[audioId];
                this.$emit('update-content', audioId);
                return
            }
            const loading = this.$loading({
                lock: true,
                text: "Loading",
                spinner: "el-icon-loading",
                background: "rgba(0, 0, 0, 0.7)",
            });
            try {
                let rs = await baseRequest.get(`/admin/get-detail-audio-question-listening/${audioId}`);
                if (rs.data.status == 200) {
                    const data = rs.data.data;
                    if (data) {
                        data?.question_listening && data?.question_listening.forEach(question => {
                            question.answer_listening = question.type == 1 ? this.shuffle(question.answer_listening) : question.answer_listening;
                        })
                        this.listQuestion[audioId] = data?.question_listening;
                        this.questions = data?.question_listening;
                        this.$emit('update-content', audioId);
                    }
                }
            } catch (e) {
                console.log(e);
            } finally {
                loading.close();
            }
        },
        saveResult() {
            const _results = this.results && Object.keys(this.results).map(questionId => {
                const answers = this.results[questionId];
                if(Object.keys(answers).length < this.answerEachInputQuestion[questionId]) {
                    return false;
                } else {
                    const isAllTrue =  answers && Object.values(answers)?.every(answer => answer == true);
                    return isAllTrue
                }
            })
            const correctAnswers = _results && _results.filter(val => val === true).length;
            const type = `result_${this.skill}`;
            const score = `${correctAnswers}/${this.questionCount}`;

            localStorage.setItem(type, score);
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
        }
    },
    watch: {
        selectedTopic() {
            this.selectedTopic?.file_type !== 'video' && this.$refs.audio?.load()
            this.selectedTopic?.file_type == 'video' && this.$refs.video?.load()
        },
        topics: {
            handler(newTopics) {
                // reset data
                this.selectedAnswers = {};
                this.questionDone = [];
                this.correctAnswers = {};
                this.selectedIndex = 0;
                this.selectedTopicIndex = 0;
                this.questionCount = 0;
                // this.questions = {}
                if (newTopics && newTopics.length) {
                    this.selectedTopic = newTopics[this.selectedTopicIndex];
                    this.getAudioDetail(this.selectedTopic?.id);
                    newTopics.forEach(topic => {
                        topic.question_listening.forEach(q => {
                            this.answerEachInputQuestion[q.id] = q.question.split("#").length - 1
                        })
                        this.questionCount += topic?.question_listening?.length;
                    })
                } else {
                    this.selectedTopic = null
                }
            },
            immediate: true,
            deep: true
        },
        questions(newQuestions) {
            this.selectedIndex = 0;
            this.selectedQuestion = this.questions[this.selectedIndex];
        }
    },
    mounted() {
    },
}
</script>

<style>
.button-active {
    background-color: #3772ff1a;
    border-radius: 8px;
    padding: 12px 16px;
    color: #2162ff;
    font-size: 18px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    flex-grow: 1;
    cursor: pointer;
}

.button-inactive {
    padding: 12px 16px;
    color: #141416;
    font-size: 18px;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: center;
    flex-grow: 1;
    cursor: pointer;
}
.player-button {
    background-color: transparent;
    border: 0;
    width: 3em;
    height: 3em;
    cursor: pointer;
    padding: 0;
}

.sound-button {
    background-color: transparent;
    border: 0;
    width: 2em;
    height: 2em;
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

.controls {
    display: flex;
    flex-direction: row;
    align-items: center;
    width: 100%;
}
</style>