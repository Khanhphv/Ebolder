<template>
    <div class="bg-white p-3 flex flex-column md:rounded" style="min-height: 100%;">
        <div class="font-bold text-2xl mb-4"> Questions</div>
        <div v-show="this.questions?.length > 0" class="flex flex-column flex-grow">
            <div class="flex flex-row flex-wrap mb-4 gap-3">
                <div v-for="(item, index) in this.questions" :key="item.id"
                    class="cursor-pointer rounded-full w-7 h-7 sm:w-10 sm:h-10  flex items-center justify-center font-semibold text-sm"
                    @click="handleSelectQuestion(index)"
                    :class="[ selectedIndex != index && questionDone[item.id] ? 'bg-[#35509A] text-white': (selectedIndex == index ? `bg-${lessonType} text-white` : 'bg-[#E6E8EC]')]">
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
            <!-- , 'border-[#E6E8EC]': isQuestionAnswered(answer?.id, selectedQuestion?.id) == false -->
            <div class="flex-grow" v-show="selectedQuestion?.type == 1">
                <div v-for="(answer, index) in selectedQuestion?.answers" :key="index" class="rounded-[8px] border-2 p-3 mb-3"
                    :class="[isQuestionAnswered(answer?.id, selectedQuestion?.id) ? `border-${lessonType} radio-${lessonType}` : ''] "
                    @click="handleSelectAnswer(answer?.id, selectedQuestion?.id)">
                    <input type="radio" :id="`test${answer?.id}`" :value="answer?.id" :checked="isQuestionAnswered(answer?.id, selectedQuestion?.id)" />
                    <label class="text-lg font-semibold ml-1">
                        {{ convertToCharacter(index) }}
                    </label>
                    <p class="font-light">{{ answer?.text }}</p>
                </div>
            </div>
            <div class="w-full flex flex-row mt-4">
                <div v-show="selectedIndex <= this.questions?.length - 1 && selectedIndex > 0" class="button-back"
                    @click="handleSelectQuestion(selectedIndex - 1)">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.5303 7.46967C10.8232 7.76256 10.8232 8.23744 10.5303 8.53033L7.06066 12L10.5303 15.4697C10.8232 15.7626 10.8232 16.2374 10.5303 16.5303C10.2374 16.8232 9.76256 16.8232 9.46967 16.5303L5.46967 12.5303C5.17678 12.2374 5.17678 11.7626 5.46967 11.4697L9.46967 7.46967C9.76256 7.17678 10.2374 7.17678 10.5303 7.46967Z" fill="#141416"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M5.25 12C5.25 11.5858 5.58579 11.25 6 11.25L18 11.25C18.4142 11.25 18.75 11.5858 18.75 12C18.75 12.4142 18.4142 12.75 18 12.75L6 12.75C5.58579 12.75 5.25 12.4142 5.25 12Z" fill="#141416"/>
                    </svg>
                    Back
                </div>
                <div v-show="selectedIndex < this.questions?.length - 1" :class="[`button bg-${lessonType}-tag`]"
                    @click="handleSelectQuestion(selectedIndex + 1)">
                    Next
                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M13.9697 16.5303C13.6768 16.2374 13.6768 15.7626 13.9697 15.4697L17.4393 12L13.9697 8.53033C13.6768 8.23744 13.6768 7.76256 13.9697 7.46967C14.2626 7.17678 14.7374 7.17678 15.0303 7.46967L19.0303 11.4697C19.3232 11.7626 19.3232 12.2374 19.0303 12.5303L15.0303 16.5303C14.7374 16.8232 14.2626 16.8232 13.9697 16.5303Z" fill="#D35924"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19.25 12C19.25 12.4142 18.9142 12.75 18.5 12.75L6.5 12.75C6.08579 12.75 5.75 12.4142 5.75 12C5.75 11.5858 6.08579 11.25 6.5 11.25L18.5 11.25C18.9142 11.25 19.25 11.5858 19.25 12Z" fill="#D35924"/>
                    </svg>

                </div>
            </div>
            <div class="w-full flex flex-row mt-4">
                <div :class="[`button bg-${lessonType}-tag`]" @click="submit">
                    Finish
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    props: ["questions", "onSubmit" , "lessonType"],
    data() {
        return {
            selectedIndex: 0,
            selectedQuestion: this.questions?.[0],
            selectedAnswerId: 0,
            selectedAnswers: {},
            inputAnswerValues: {},
            questionCount: 0,
            questionDone: {},
            inputAnswersByQuestion: {},
            inputAnswers: {},
            typingAnswers: {},
            answerEachInputQuestion: {},
            results: {},
            arrowLeft: require('../../../../../public/images/learn/arrow-left.svg'),
            arrowRight: require('../../../../../public/images/learn/arrow-right.svg')
        }
    },
    methods: {
        handleSelectQuestion(index) {
            this.selectedIndex = index;
            this.selectedQuestion = this.questions?.[this.selectedIndex];
        },
        convertToCharacter(number) {
            return String.fromCharCode(65 + number)
        },
        handleSelectAnswer(answerId, questionId) {
            this.selectedAnswerId = answerId;
            const rightAnswer = this.selectedQuestion.right_answers.answer_id;

            this.$set(this.results, questionId, {});

            if(answerId) {
                this.$set(this.selectedAnswers, questionId, answerId);
                if (answerId == rightAnswer) {
                    this.results[questionId][answerId] = true;
                } else {
                    this.results[questionId][answerId] = false;
                }
            }
            this.questionDone[questionId] = true;
            this.isQuestionAnswered(answerId, questionId);

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
        handleAnswerInput(event, question, elementIndex) {
            // get index of input
            const inputIndex = this.findCollectionIndex(document.getElementById('question').getElementsByTagName('input'), event.target.id)
            const answerId = question.answers[inputIndex].answer_id;
            const rightAnswer = question.answers[inputIndex].text.toLowerCase().trim();
            const inputText = event.target.value ? event.target.value.toLowerCase().trim() : '';
            const questionId = question.id;
            // store value of each input
            this.$set(this.inputAnswerValues, `${elementIndex}${question.id}`, event.target.value)
            this.$set(this.typingAnswers, questionId, event.target.value)

            if (!this.results[questionId]) {
                this.$set(this.results, questionId, {});
            }

            if(rightAnswer == inputText) {
                this.results[questionId][answerId] = true;
            } else {
                this.results[questionId][answerId] = false;
            }

            if(event.target.value || event.target.value != '' ) {
                this.questionDone[question.id] = true;
            } else {
                this.questionDone[question.id] = false;
            }
        },
        findCollectionIndex(collectionsArray, targetId) {
            for (let i = 0; i < collectionsArray.length; i++) {
                if (collectionsArray[i].id === targetId) {
                    return i;
                }
            }
            return -1;
        },
        async submit() {
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
            this.onSubmit(correctAnswers, this.questionCount, this.results, this.selectedAnswers, this.typingAnswers);
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
                this.results = {};
                this.questionDone = {};
                this.inputAnswerValues = {};

                this.selectedQuestion = newQuestions[this.selectedIndex];
                newQuestions.forEach(question => {
                    this.answerEachInputQuestion[question.id] = question.question.split("#").length - 1;
                    this.questionCount += 1
                })
            }
        }
    }
}
</script>

<style>
.button {
    border-radius: 8px;
    padding: 12px 16px;
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
