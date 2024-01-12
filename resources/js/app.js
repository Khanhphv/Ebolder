/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue");
window.$ = require("jquery");
window.JQuery = require("jquery");
import { Api, timeWork } from "./utils/Api";
import { Helper } from "./utils/Helper";
window.$Api = Api;
window.$TimeWork = timeWork;
window.$Helper = Helper;
import ElementUI from "element-ui";
import "element-ui/lib/theme-chalk/index.css";
Vue.use(ElementUI);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component(
    "question-create",
    require("./components/admin/vocabulary/QuestionCreate.vue").default
);
Vue.component(
    "question-list",
    require("./components/admin/vocabulary/QuestionList.vue").default
);
Vue.component(
    "vocabulary-detail",
    require("./components/admin/vocabulary/VocabularyDetail.vue").default
);
Vue.component(
    "vocabulary-list",
    require("./components/admin/vocabulary/VocabularyList.vue").default
);
//listening
Vue.component(
    "question-listening-list",
    require("./components/admin/listening/QuestionList.vue").default
);
Vue.component(
    "question-listening-create",
    require("./components/admin/listening/QuestionCreate.vue").default
);
Vue.component(
    "question-listening-detail",
    require("./components/admin/listening/QuestionDetail.vue").default
);
Vue.component(
    "topic-listening-list",
    require("./components/admin/listening/ListeningList.vue").default
);
Vue.component(
    "listening-detail",
    require("./components/admin/listening/ListeningDetail.vue").default
);
//Reading
Vue.component(
    "topic-reading-list",
    require("./components/admin/reading/ReadingList.vue").default
);
Vue.component(
    "topic-reading-create",
    require("./components/admin/reading/TopicCreate.vue").default
);
Vue.component(
    "topic-reading-detail",
    require("./components/admin/reading/TopicDetail.vue").default
);

// Pronunciation
Vue.component(
    "topic-pronunciation-list",
    require("./components/admin/pronunciation/PronunciationList.vue").default
);
Vue.component(
    "topic-pronunciation-create",
    require("./components/admin/pronunciation/TopicCreate.vue").default
);
Vue.component(
    "topic-pronunciation-detail",
    require("./components/admin/pronunciation/TopicDetail.vue").default
);
//grammar
Vue.component(
    "question-grammar-create",
    require("./components/admin/grammar/QuestionCreate.vue").default
);
Vue.component(
    "question-grammar-list",
    require("./components/admin/grammar/QuestionList.vue").default
);
Vue.component(
    "grammar-detail",
    require("./components/admin/grammar/GrammarDetail.vue").default
);
Vue.component(
    "grammar-list",
    require("./components/admin/grammar/GrammarList.vue").default
);

//Speak
Vue.component(
    "question-speak-create",
    require("./components/admin/speak/QuestionCreate.vue").default
);
Vue.component(
    "question-speak-list",
    require("./components/admin/speak/QuestionList.vue").default
);
Vue.component(
    "speak-detail",
    require("./components/admin/speak/SpeakDetail.vue").default
);
Vue.component(
    "speak-list",
    require("./components/admin/speak/SpeakList.vue").default
);
Vue.component(
    "question-speak-luyen-am",
    require("./components/admin/speak/QuestionLuyenAm.vue").default
);

Vue.component(
    "page-speak-luyen-am",
    require("./components/frontend/speaking/PageLuyenAm.vue").default
);
//user
Vue.component(
    "user-list",
    require("./components/admin/user/UserList.vue").default
);
Vue.component(
    "user-create",
    require("./components/admin/user/UserCreate.vue").default
);
Vue.component(
    "user-edit",
    require("./components/admin/user/UserEdit.vue").default
);
Vue.component(
    "history-exam",
    require("./components/admin/user/History.vue").default
);

Vue.component(
    "history-learn-admin",
    require("./components/admin/user/HistoryLearn.vue").default
);

//role
Vue.component(
    "role-list",
    require("./components/admin/role/RoleList.vue").default
);
Vue.component(
    "role-create",
    require("./components/admin/role/RoleCreate.vue").default
);
Vue.component(
    "role-edit",
    require("./components/admin/role/RoleEdit.vue").default
);

//exam
Vue.component(
    "exam-list",
    require("./components/admin/exam/ExamList.vue").default
);

Vue.component(
    "exam-list-enable",
    require("./components/frontend/exam/ExamList.vue").default
);

Vue.component(
    "exam-detail",
    require("./components/frontend/exam/ExamDetail.vue").default
);

//frontend
//header
Vue.component(
    "header-component",
    require("./components/frontend/header.vue").default
);
Vue.component(
    "main-header-component",
    require("./components/frontend/MainHeader.vue").default
);
//footer
Vue.component(
    "footer-component",
    require("./components/frontend/Footer.vue").default
);
//home
Vue.component(
    "home-page-component",
    require("./components/frontend/homePage/Home.vue").default
);
//testPage
Vue.component(
    "test-page-component",
    require("./components/frontend/testPage/TestPage.vue").default
);
//vocabulary test
Vue.component(
    "vocabulary-test",
    require("./components/frontend/vocabulary/VocabularyTest.vue").default
);
//grammar
Vue.component(
    "grammar-test",
    require("./components/frontend/grammar/GrammarTest.vue").default
);
//reading
Vue.component(
    "reading-test",
    require("./components/frontend/reading/ReadingTest.vue").default
);

//Pronunciation
Vue.component(
    "pronunciation-test",
    require("./components/frontend/pronunciation/PronunciationTest.vue").default
);
//listening
Vue.component(
    "listening-test",
    require("./components/frontend/listening/ListeningTest.vue").default
);

Vue.component(
    "speaking-test",
    require("./components/frontend/speaking/SpeakingTest.vue").default
);
//error page
Vue.component(
    "error-page",
    require("./components/frontend/error/ErrorPage.vue").default
);

//learn page
Vue.component(
    "learn-page",
    require("./components/frontend/learn/LearnPage.vue").default
);
//learn admin page
Vue.component(
    "learn-list",
    require("./components/admin/learn/LearnList.vue").default
);
Vue.component(
    "lesson-topic-create",
    require("./components/admin/learn/TopicCreate.vue").default
);
Vue.component(
    "lesson-topic-detail",
    require("./components/admin/learn/TopicDetail.vue").default
);

//level page
Vue.component(
    "level-list",
    require("./components/admin/level/LevelList.vue").default
);
//lesson page
Vue.component(
    "lesson-page",
    require("./components/frontend/lesson/LessonPage.vue").default
);

//History
Vue.component(
    "history-page",
    require("./components/frontend/history/History.vue").default
);

Vue.component(
    "history-learn-page",
    require("./components/frontend/history/HistoryLearn.vue").default
);

//choose-action 

Vue.component(
    "choose-action",
    require("./components/frontend/chooseAction/ChooseAction.vue").default
);
//result
Vue.component(
    "result-exam",
    require("./components/frontend/result/Result.vue").default
); 
//result
Vue.component(
    "features",
    require("./components/frontend/features/Features.vue").default
);
Vue.component(
    "popup-dialog",
    require("./components/frontend/PopupDialog.vue").default
);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
});
