<template>
    <div class="bg-white p-3 flex flex-column md:rounded min-h-full">
        <div class="font-bold text-xl mb-4"> Questions</div>
        <div v-show="this.questions?.length > 0">
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
            <p class="font-semibold" id="question">
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
        <div class="flex-grow"  v-show="selectedQuestion?.type == 1">
            <div v-for="(answer, index) in selectedQuestion?.answers" :key="index" class="rounded-[8px] border-2 p-3 mb-3"
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
            <div :class="{'button-next': selectedIndex <= this.questions?.length - 1 && selectedIndex > 0, 'button-back': selectedIndex == 0 }"
                @click="handleSelectQuestion(selectedIndex - 1, 'back')">
                <Left :color="selectedIndex <= this.questions?.length - 1 && selectedIndex > 0 ? activeColor : inactiveColor"/>
                Back
            </div>
            <div class="w-3"></div>
            <div :class="{'button-next': selectedIndex < this.questions?.length - 1, 'button-back': selectedIndex == this.questions?.length - 1}"
                @click="handleSelectQuestion(selectedIndex + 1, 'next')">
                Next
                <Right :color="selectedIndex < this.questions?.length - 1 ? activeColor : inactiveColor" />
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import Right from '../../../svg/Right.vue';
import Left from '../../../svg/Left.vue';
export default {
    components: {
        Right,
        Left
    },
    props: ["questions", "skill", "onSubmit", "value"],
    data() {
        return {
            activeColor: '#2162ff',
            inactiveColor: '#141416',
            selectedIndex: 0,
            selectedQuestion: this.questions?.[0],
            selectedAnswerId: 0,
            selectedAnswers: {},
            questionCount: 0,
            questionDone: {},
            inputAnswerValues: {},
            inputAnswersByQuestion: {},
            inputAnswers: {},
            answerEachInputQuestion: {},
            results: {},
            arrowLeft: require('../../../../../public/images/learn/arrow-left.svg'),
            arrowRight: require('../../../../../public/images/learn/arrow-right.svg')
        }
    },
    methods: {
        handleSelectQuestion(index, action) {
            if (action == 'back' && this.selectedIndex <= 0 || action == 'next' && this.selectedIndex >= this.questions?.length - 1) {
                return;
            }
            this.selectedIndex = index;
            this.selectedQuestion = this.questions?.[this.selectedIndex];
        },
        convertToCharacter(number) {
            return String.fromCharCode(65 + number)
        },
        handleSelectAnswer(answerId, questionId) {
            this.selectedAnswerId = answerId;
            const rightAnswer = this.selectedQuestion.right_answers.answer_id;
            this.$emit('update-selected-answer', questionId, answerId);

            this.$set(this.results, questionId, {});

            if(answerId) {
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
            const answerId = question.answers[inputIndex].answer_id;
            const rightAnswer = question.answers[inputIndex].text.toLowerCase().trim();
            const inputText = event.target.value ? event.target.value.toLowerCase().trim() : '';
            const questionId = question.id;
            // store value of each input
            this.$set(this.inputAnswerValues, `${elementIndex}${question.id}`, event.target.value)
            this.$emit('update-input-answer', questionId, inputText);

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
    },
    watch: {
        questions(newQuestions) {
            if (newQuestions && newQuestions.length) {
                // reset data
                this.selectedAnswers = {};
                this.correctAnswers = {};
                this.selectedIndex = 0;
                this.questionCount = 0;

                this.selectedQuestion = newQuestions[this.selectedIndex];
                newQuestions.forEach(question => {
                    this.answerEachInputQuestion[question.id] = question.question.split("#").length - 1;
                    this.questionCount += 1
                })
            }
        },
        "skill" : {
            handler(value) {
                this.results = {};
            }
        }
    },

    created() {
      console.log('f');
      if (this.questions && this.questions.length) {
        console.log('fx');
        // reset data
        this.selectedAnswers = {};
        this.correctAnswers = {};
        this.selectedIndex = 0;
        this.questionCount = 0;

        this.selectedQuestion = this.questions[this.selectedIndex];
        this.questions.forEach(question => {
          this.answerEachInputQuestion[question.id] = question.question.split("#").length - 1;
          this.questionCount += 1
        })
      }
    }
}
</script>

<style>
.button-next {
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

.button-back {
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
</style>