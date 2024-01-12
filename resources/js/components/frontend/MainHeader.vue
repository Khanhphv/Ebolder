<template>
    <div>
        <div class="flex items-center py-2 md:py-[24px] px-4 justify-between relative z-10 border-b-2 header"
            style="font-family: lexend !important;">
            <div class="flex items-center cursor-pointer">
                <img width="46px" height="46px" src="/images/landing/logo.svg" alt="" @click="backToHome"/>
                <img :src="home" @click="backToHome" class="home"/>
                <div class="flex items-center justify-center px-4 py-2 rounded-[100px] bg-[#E6E8EC] breadscrumb">
                    <div v-if="breadcrumb && breadcrumb.length" v-for="(item, index) in breadcrumb" :key="item.label" @click="backToExam(breadcrumb)"
                        class="flex items-center justify-center">
                        <img class="mr-3" :src="item.icon" />
                        {{ item?.label }}
                        <img class="ml-2" :src="right" v-show="index < breadcrumb.length - 1" />
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap py-2 items-center">
                <div class="flex items-center justify-center px-3 h-[44px] rounded-[100px] bg-[#2162FF] text-white count-down"
                    v-show="showClock">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.75C8.54822 6.75 5.75 9.54822 5.75 13C5.75 16.4518 8.54822 19.25 12 19.25C15.4518 19.25 18.25 16.4518 18.25 13C18.25 9.54822 15.4518 6.75 12 6.75ZM4.25 13C4.25 8.71979 7.71979 5.25 12 5.25C16.2802 5.25 19.75 8.71979 19.75 13C19.75 17.2802 16.2802 20.75 12 20.75C7.71979 20.75 4.25 17.2802 4.25 13Z" fill="white"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 8.75C12.4142 8.75 12.75 9.08579 12.75 9.5V12.6395L14.9685 14.4143C15.292 14.6731 15.3444 15.1451 15.0857 15.4685C14.8269 15.792 14.3549 15.8444 14.0315 15.5857L11.5315 13.5857C11.3536 13.4433 11.25 13.2278 11.25 13V9.5C11.25 9.08579 11.5858 8.75 12 8.75Z" fill="white"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M8.25 2C8.25 1.58579 8.58579 1.25 9 1.25H15C15.4142 1.25 15.75 1.58579 15.75 2C15.75 2.41421 15.4142 2.75 15 2.75H9C8.58579 2.75 8.25 2.41421 8.25 2Z" fill="white"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 1.25C12.4142 1.25 12.75 1.58579 12.75 2L12.75 6C12.75 6.41421 12.4142 6.75 12 6.75C11.5858 6.75 11.25 6.41421 11.25 6L11.25 2C11.25 1.58579 11.5858 1.25 12 1.25Z" fill="white"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19.5303 6.46967C19.8232 6.76256 19.8232 7.23744 19.5303 7.53033L18.0303 9.03033C17.7374 9.32322 17.2626 9.32322 16.9697 9.03033C16.6768 8.73744 16.6768 8.26256 16.9697 7.96967L18.4697 6.46967C18.7626 6.17678 19.2374 6.17678 19.5303 6.46967Z" fill="white"/>
                    </svg>
                    <VueCountdown :auto-start="true" :time="timeWork" @progress="handleCountdownProgress">
                        <template slot-scope="props">{{ props.minutes }}:{{ props.seconds <= 9 ? `0${props.seconds}` :
                            props.seconds }} </template>
                    </VueCountdown>
                    <div @click="handleExam" class="cursor-pointer">&nbsp | {{ action }} </div>
                </div>
                <nav class="relative flex flex-wrap items-center justify-between px-2">
                    <div class="flex mx-auto flex-row items-center justify-between">
                        <div class="w-fit rounded-[100px] bg-white px-4 py-2 cursor-pointer" @click="openLink"
                            v-show="!user">
                            <div class="text-lg leading-relaxed inline-block whitespace-nowrap	">
                                Sign in
                            </div>
                        </div>
                        <el-dropdown trigger="click" @command="handleCommand" v-if="user">
                            <div class="flex items-center justify-center cursor-pointer">
                                <span class="mr-2">{{ user ? user.name : "" }}</span><Avatar />
                            </div>
                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item command="history">
                                        History
                                    </el-dropdown-item>
                                    <el-dropdown-item command="logout">
                                        Logout
                                    </el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </div>
                </nav>
            </div>
        </div>
        <div class="fixed flex w-full h-full top-0 items-center justify-center" v-if="isOpen" :style="{zIndex: 100}">

            <transition name="fade">
                <div>
                    <div class="absolute inset-0 bg-gray-600 opacity-70 " @click="handleToggle"></div>

                    <div class="w-full max-w-lg p-3 relative mx-auto my-auto rounded-xl shadow-lg bg-white">
                        <div>
                            <div class="text-center p-3 flex-auto justify-center leading-6">
                                <h2 class="text-2xl font-bold py-4">Are you sure?</h2>
                                <p class="text-md text-gray-500 px-8">
                                    Do you really want to finish this test?
                                </p>
                            </div>
                            <div class="p-3 mt-2 text-center space-x-4 md:block">
                                <button
                                    class="mb-2 md:mb-0 bg-white px-5 py-2 text-sm shadow-sm font-medium tracking-wider border text-gray-600 rounded-md hover:shadow-lg hover:bg-gray-100"
                                    @click="handleToggle">
                                    Close
                                </button>
                                <button @click="handleOk"
                                    class="mb-2 md:mb-0 bg-blue-500 border border-blue-500 px-5 py-2 text-sm shadow-sm font-medium tracking-wider text-white rounded-md hover:shadow-lg hover:bg-blue-600">
                                    OK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>
import VueCountdown from "@chenfengyuan/vue-countdown";
import Avatar from '../../svg/Avatar.vue';
export default {
    components: {
        VueCountdown,
        Avatar
    },
    props: ["user", "breadcrumb", "showTime", "onFinish", "value"],
    data() {
        return {
            home: require('../../../../public/images/header/home.svg'),
            right: require('../../../../public/images/header/right.svg'),
            timer: require('../../../../public/images/header/timer.svg'),
            timeWork: 0,
            showClock: this.showTime || false,
            timerun: 0,
            action: 'Next',
            skills: [
                'Listening', 'Speaking', 'Reading', 'Writing'
            ],
            isOpen: false,
        }
    },
    methods: {
        handleCommand(e) {
            if (e === "history") {
                window.location.href = "/history";
            } else if (e === 'logout') {
                window.location.href = "/logout";
            }
        },
        openLink() {
            window.location.href = `${$Api.baseUrl}/sign-in`;
        },
        logout() {
            window.location.href = "/logout";
        },
        history() {
            window.location.href = "/history";
        },
        learn() {
            window.location.href = "/history-learn";
        },
        backToHome() {
            window.location.href = "/";
        },
        backToExam(breadcrumb) {
            if (breadcrumb.length > 0) {
                if (breadcrumb[0].label == 'Exam') {
                    window.location.href = "/exam";
                } else {
                    window.location.href = "/";
                }
            } else {
                window.location.href = "/";
            }
        },
        handleCountdownProgress(data) {
            this.timerun = this.timeWork - data.totalMilliseconds + 1000;
            if (this.timerun === this.timeWork) {
                this.handleExam(false);
            }
        },
        handleExam(isConfirm = true) {
            const indexOfSkill = this.skills.findIndex(skill => skill.toLowerCase() == this.value);
            if (indexOfSkill < this.skills.length - 1) {
                this.$emit('handleExam', this.skills[indexOfSkill + 1].toLowerCase());
            } else {
                isConfirm && this.handleToggle()
                if(!isConfirm) {
                    this.showClock = false;
                    this.onFinish();
                }
            }
        },
        handleOk() {
            this.showClock = false
            this.onFinish();
            this.isOpen = !this.isOpen;
        },
        handleToggle() {
            this.isOpen = !this.isOpen;
        },
    },
    watch: {
        value(newValue) {
            const timeWorkMapping = {
                'writing': 60 * 60 * 1000,
                'listening': 40 * 60 * 1000,
                'reading': 60 * 60 * 1000 + 1,
            }
            if (!newValue) return;
            this.timeWork = timeWorkMapping[newValue];

            if (newValue == 'writing') {
                this.action = "Finish";
            } else {
                this.action = "Next";
            }
        }
    }
};
</script>
<style>
@media only screen and (max-width: 900px) {

    .home,
    .breadscrumb {
        display: none;
    }
}

a,
a:hover {
    text-decoration: none;
    color: unset;
}

.header {
    border: 2px solid #F4F5F6
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 500ms ease-out;
}
</style>
  