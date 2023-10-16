<template>
    <div>
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="lnr-book icon-gradient bg-mean-fruit"></i>
                    </div>
                    <div>
                        <p>VOCABULARY LEVEL TEST</p>
                    </div>
                </div>
                <div class="page-title-actions" @click="createQuestion">
                    <span class="btn-icon-wrapper pr-2">
                        <p
                            class="btn-icon btn dev-button btn-primary"
                            style="padding: 10px 15px"
                        >
                            SAVE
                        </p>
                    </span>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="flex flex-col justify-center w-full items-center">
                <el-form :model="topicData" :rules="rules" ref="ruleFormName" class="w-full">
                    <div class="my-2">
                    <el-form-item label="Name Topic" prop="name" :rules="[
                        {
                            required: true,
                            message:
                                'Please enter name topic',
                        },
                    ]">
                        <el-input
                        placeholder="Name Topic"
                        v-model="topicData.name"
                        ></el-input>
                    </el-form-item>
                    </div>
                    <div class="">
                    <editor
                        v-model="topicData.description"
                        api-key="hri1xykfk0d1gnrwf70v71zn81p6f7s5e3z1edxly9mansfq"
                        :init="{
                        height: 600,
                        menubar: false,
                        plugins: [
                            'advlist autolink lists link image charmap print preview anchor',
                            'searchreplace visualblocks code fullscreen',
                            'insertdatetime media table paste code help wordcount',
                        ],
                        toolbar:
                            'undo redo | formatselect | bold italic backcolor | \
                        alignleft aligncenter alignright alignjustify | \
                        bullist numlist outdent indent | removeformat | help',
                        visual: false,
                        content_style: `
                        table, th, td {
                            border: 1px solid #000 !important;
                        }	`,
                        paste_data_images: true,
                        }"
                    />
                    <!-- <el-form-item label="Description" prop="description" :rules="[
                        {
                            required: true,
                            message:
                                'Please enter description',
                        },
                    ]">
                        <el-input
                        type="textarea"
                        placeholder="Description"
                        rows="3"
                        v-model="topicData.description"
                        ></el-input>
                    </el-form-item> -->
                    </div>
                    <div class="">
                    <el-form-item label="Exam" prop="isExam">
                        <el-switch v-model="topicData.isExam"></el-switch>
                    </el-form-item>
                    </div>

                    <div class="flex justify-end items-center mt-4">
                    <!-- <el-button plain @click="resetFeild">Cancel</el-button> -->
                    </div>
                </el-form>
                <div
                    class="card w-full mt-3"
                    v-for="(data, index) in dataQuestion"
                    :key="index"
                >
                    <div class="card-header flex justify-between items-center">
                        <span>Question {{ index + 1 }}</span>
                        <span
                            v-if="dataQuestion.length > 1"
                            class="
                                text-red-600 text-[14px]
                                font-semibold
                                cursor-pointer
                            "
                            @click="deleteQues(data.id)"
                            >Xóa</span
                        >
                    </div>

                    <div class="card-body" v-if="data.type == 1">
                        <div class="w-full">
                            <el-form
                                ref="ruleFormData"
                                :model="data"
                                class="w-full"
                            >
                                <el-form-item
                                    prop="question"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Please enter your question',
                                        },
                                    ]"
                                    class="w-full m-0"
                                >
                                    <el-input
                                        type="textarea"
                                        placeholder="Nhập câu hỏi..."
                                        v-model="data.question"
                                        rows="3"
                                    ></el-input>
                                </el-form-item>

                                <div
                                    class="w-full mt-2"
                                    v-for="item in data.dataAns"
                                    :key="item.idAns"
                                >
                                    <div class="mt-4 flex items-start">
                                        <el-form
                                            ref="ruleFormItem"
                                            :model="item"
                                            :rules="rules"
                                            class="w-full"
                                        >
                                            <el-form-item
                                                prop="text"
                                                :rules="[
                                                    {
                                                        required: true,
                                                        message:
                                                            'Please enter your answer',
                                                    },
                                                ]"
                                                class="w-full m-0"
                                            >
                                                <Input v-model="item.text">
                                                    <template slot="prepend">{{
                                                        item.alphabet
                                                    }}</template>
                                                </Input>
                                            </el-form-item>
                                        </el-form>

                                        <el-button
                                            v-if="data.dataAns.length > 1"
                                            class="ml-2"
                                            type="danger"
                                            icon="el-icon-delete"
                                            @click="
                                                deleteAns(data.id, item.idAns)
                                            "
                                            plain
                                            circle
                                        ></el-button>
                                    </div>
                                </div>
                                <div
                                    class="
                                        flex
                                        justify-between
                                        items-start
                                        mt-4
                                    "
                                >
                                    <div class="leading-[40px]">
                                        <span class="text-[13px] font-semibold"
                                            >Level:
                                            {{
                                                data.level == 1
                                                    ? "Easy"
                                                    : data.level == 2
                                                    ? "Medium"
                                                    : "Hard"
                                            }}</span
                                        >
                                        <star-rating
                                            :star-size="20"
                                            :animate="true"
                                            v-model="data.level"
                                            :show-rating="false"
                                            :max-rating="3"
                                        />
                                    </div>

                                    <div class="w-[180px]">
                                        <el-form-item
                                            :rules="[
                                                {
                                                    required: true,
                                                    message:
                                                        'Please select answer',
                                                    trigger: 'blur',
                                                },
                                            ]"
                                            prop="answer"
                                            class="w-full m-0"
                                        >
                                            <span
                                                class="
                                                    text-[13px]
                                                    font-semibold
                                                    mr-2
                                                "
                                                >Anwser:</span
                                            >
                                            <el-select
                                                v-model="data.answer"
                                                placeholder="Select"
                                            >
                                                <el-option
                                                    v-for="item in data.dataAns"
                                                    :key="item.idAns"
                                                    :label="item.alphabet"
                                                    :value="item.idAns"
                                                >
                                                </el-option>
                                            </el-select>
                                        </el-form-item>
                                    </div>
                                </div>
                                <div
                                    class="
                                        mx-auto
                                        my-4
                                        lg:my-2
                                        flex
                                        justify-center
                                    "
                                >
                                    <el-button
                                        v-if="data.dataAns.length < maxAns"
                                        @click="pushAns(data.id)"
                                        icon="el-icon-plus"
                                        plain
                                    ></el-button>
                                </div>
                            </el-form>
                        </div>
                    </div>
                    <div class="card-body" v-if="data.type == 2">
                        <div class="w-full">
                            <el-form
                                ref="ruleFormData"
                                :model="data"
                                class="w-full"
                            >
                                <el-form-item
                                    prop="question"
                                    :rules="[
                                        {
                                            required: true,
                                            message:
                                                'Please enter your question',
                                        },
                                    ]"
                                    class="w-full m-0"
                                >
                                    <el-input
                                        type="textarea"
                                        placeholder="Nhập câu hỏi..."
                                        v-model="data.question"
                                        rows="3"
                                    ></el-input>
                                </el-form-item>
                                <div class="mt-4">
                                    <el-button
                                        icon="el-icon-search"
                                        type="primary"
                                        plain
                                        @click="renderAnswer(data, index)"
                                    >
                                        Xuất câu trả lời
                                    </el-button>
                                </div>

                                <div
                                    class="w-full mt-2"
                                    v-for="item in data.dataAns"
                                    :key="item.idAns"
                                >
                                    <div class="mt-4 flex items-start">
                                        <el-form
                                            ref="ruleFormItem"
                                            :model="item"
                                            :rules="rules"
                                            class="w-full"
                                        >
                                            <el-form-item
                                                prop="text"
                                                :rules="[
                                                    {
                                                        required: true,
                                                        message:
                                                            'Please enter your answer',
                                                    },
                                                ]"
                                                class="w-full m-0"
                                            >
                                                <Input v-model="item.text">
                                                    <template slot="prepend"
                                                        >{{ item.alphabet }}
                                                    </template>
                                                </Input>
                                            </el-form-item>
                                        </el-form>
                                    </div>
                                </div>
                                <div
                                    class="
                                        flex
                                        justify-between
                                        items-start
                                        mt-4
                                    "
                                >
                                    <div class="leading-[40px]">
                                        <span class="text-[13px] font-semibold"
                                            >Level:
                                            {{
                                                data.level == 1
                                                    ? "Easy"
                                                    : data.level == 2
                                                    ? "Medium"
                                                    : "Hard"
                                            }}</span
                                        >
                                        <star-rating
                                            :star-size="20"
                                            :animate="true"
                                            v-model="data.level"
                                            :show-rating="false"
                                            :max-rating="3"
                                        />
                                    </div>
                                </div>
                            </el-form>
                        </div>
                    </div>
                </div>

                <div class="mx-auto my-4 flex justify-center">
                    <el-popover placement="right" trigger="click">
                        <p class="mb-2 text-[14px] text-bold">Dạng câu hỏi:</p>
                        <el-button type="primary" @click="pushQues(1)" round
                            >Chọn đáp án
                        </el-button>
                        <el-button type="primary" @click="pushQues(2)" round
                            >Điền vào chỗ trống
                        </el-button>
                        <el-button
                            type="success"
                            icon="el-icon-circle-plus-outline"
                            plain
                            slot="reference"
                            >More questions
                        </el-button>
                    </el-popover>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import baseRequest from "../../../utils/baseRequest";
import StarRating from "vue-star-rating";
import { Input, Button, Select, Form } from "element-ui";
import Editor from "@tinymce/tinymce-vue";

export default {
    components: {
        StarRating,
        Input,
        Button,
        Select,
        Form,
        Editor,
    },
    data() {
        return {
            dataQuestion: [],
            alphabet: ["a", "b", "c", "d", "e", "f", "g", "h"],
            maxAns: 4,
            level: "Easy",

            rules: {
                name: [
                    {
                        required: true,
                        message: "Please input Activity question",
                        trigger: "blur",
                    },
                ],
            },
            topicData: {
                name: null,
                description: null,
                isExam: false
            },
        };
    },
    methods: {
        resetFeild() {
            this.topicData = {
                name: null,
                description: null,
            };
        },
        pushAns(id) {
            let dataQues = this.dataQuestion.find((item) => item.id == id);
            dataQues.dataAns.push({
                idAns: Date.now(),
                text: null,
                alphabet: this.alphabet[dataQues.dataAns.length].toUpperCase(),
            });
        },
        validate(formNameItem, formNameData, ruleFormName) {
            if (this.$refs[formNameItem] || this.$refs[formNameData] || this.$refs[ruleFormName]) {
                let isCheck = true;

                this.$refs[formNameItem].forEach((item) => {
                    item.validate((valid) => {
                        if (!valid) {
                            isCheck = false;
                        } else {
                            console.log("error submit!!");
                            return false;
                        }
                    });
                });
                if (
                    this.dataQuestion[0].type == 2 &&
                    this.dataQuestion.length == 1
                ) {
                    return true;
                } else {
                    this.$refs[formNameData].forEach((item) => {
                        item.validate((valid) => {
                            if (!valid) {
                                isCheck = false;
                            } else {
                                console.log("error submit!!");
                                return false;
                            }
                        });
                    });
                }
                return isCheck;
            } else {
                return true;
            }
        },
        pushQues(type) {
            let isCheck = this.validate("ruleFormData", "ruleFormItem");
            if (isCheck) {
                if (type == 1) {
                    this.dataQuestion.push({
                        id: Date.now(),
                        question: null,
                        level: 1,
                        type: 1,
                        dataAns: [
                            {
                                idAns: $Helper.randomId(),
                                text: null,
                                alphabet: "A",
                            },
                        ],
                        answer: null,
                    });
                } else if (type == 2) {
                    this.dataQuestion.push({
                        id: Date.now() + 1,
                        question: null,
                        level: 1,
                        dataAns: [],
                        answer: null,
                        type: type,
                    });
                }
            }
        },
        deleteAns(idQues, idAns) {
            let dataQues = this.dataQuestion.find((item) => item.id == idQues);
            dataQues.dataAns = dataQues.dataAns.filter(
                (item) => item.idAns != idAns
            );
            let data = dataQues.dataAns;
            let temp = [];
            for (let i = 0; i < data.length; i++) {
                temp.push({
                    idAns: data[i].idAns,
                    text: data[i].text,
                    alphabet: this.alphabet[i].toUpperCase(),
                });
            }
            dataQues.dataAns = temp;
        },
        deleteQues(id) {
            this.dataQuestion = this.dataQuestion.filter(
                (item) => item.id != id
            );
        },
        async createQuestion() {
            let isCheck = this.validate("ruleFormData", "ruleFormItem", "ruleFormName");
            if (isCheck) {
                const requestData = {
                    topicData: this.topicData,
                    dataQuestion: this.dataQuestion,
                };
                console.log(requestData);
                try {
                    let result = await baseRequest.post(
                        `/admin/store-question-vocabulary`,
                        requestData
                    );
                    let { data } = result;
                    if (data.status == 200) {
                        this.$message({
                            message: data.message,
                            type: "success",
                        });
                        setTimeout(() => {
                            window.location.href = `${$Api.baseUrl}/admin/volabulary-level-test`;
                        }, 1000);
                    } else {
                        this.$message({
                            message: data.message,
                            type: "error",
                        });
                    }
                } catch (error) {
                    console.log("🚀 ~ ~ error", error);
                }
            }
        },
        renderAnswer(data, index) {
            let question = data.question;
            if (question != null) {
                let sum = 0;
                this.dataQuestion[index].dataAns = [];
                if (question.length > 0) {
                    for (let i = 0; i < question.length; i++) {
                        if (question[i] === "#") {
                            sum++;
                            this.dataQuestion[index].dataAns.push({
                                alphabet: sum,
                                idAns: $Helper.randomId(),
                                text: null,
                            });
                        }
                    }
                }
            }
            return;
        },
    },

    // created() {
    //     setTimeout(() => {
    //         this.isLoading = false;
    //     }, 3000);
    // },
};
</script>
