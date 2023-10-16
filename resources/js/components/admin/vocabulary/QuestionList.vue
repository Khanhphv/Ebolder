<template>
    <div class="container">
        <LoadingVue v-if="isLoading" />
        <div class="flex flex-col justify-center w-full items-center">
            <div
                class="card w-full mt-3 mb-2"
                v-for="(data, index) in dataQuestion"
                :key="index"
                ref="card"
            >
                <div class="card-header flex justify-between items-center">
                    <span>Question {{ index + 1 }}</span>
                    <div class="flex items-center">
                        <div class="save hidden mr-2">
                            <el-button
                                size="small"
                                type="success"
                                icon="el-icon-check"
                                @click="SaveQuestion(data.id, index)"
                                circle
                            ></el-button>
                        </div>
                        <div class="edit block mr-2">
                            <el-button
                                size="small"
                                type="primary"
                                icon="el-icon-edit"
                                circle
                                @click="EditQuestion(index)"
                            ></el-button>
                        </div>

                        <el-button
                            size="small"
                            type="danger"
                            icon="el-icon-delete"
                            circle
                            @click="deleteQues(data.id)"
                            v-if="dataQuestion.length > 1"
                        ></el-button>
                    </div>
                </div>

                <div class="card-body hidden" v-if="data.type == 1">
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
                                        message: 'Please enter your question',
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
                                v-for="(item, index) in data.answers"
                                :key="item.idAns"
                            >
                                <div class="mt-4 flex items-center">
                                    <el-form
                                        ref="ruleFormItem"
                                        :model="item"
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
                                            <el-input v-model="item.text">
                                                <template slot="prepend">{{
                                                    alphabet[
                                                        index
                                                    ].toUpperCase()
                                                }}</template>
                                            </el-input>
                                        </el-form-item>
                                    </el-form>
                                    <el-button
                                        v-if="data.answers.length > 1"
                                        class="ml-2"
                                        type="danger"
                                        icon="el-icon-delete"
                                        @click="deleteAns(data.id, item.id)"
                                        circle
                                        plain
                                    ></el-button>
                                </div>
                            </div>
                            <div class="flex justify-between items-start mt-4">
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
                                                message: 'Please select answer',
                                                trigger: 'blur',
                                            },
                                        ]"
                                        prop="right_answers.answer_id"
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
                                            v-model="
                                                data.right_answers.answer_id
                                            "
                                            placeholder="Select"
                                        >
                                            <el-option
                                                v-for="(
                                                    item, index
                                                ) in data.answers"
                                                :key="item.id"
                                                :label="
                                                    alphabet[
                                                        index
                                                    ].toUpperCase()
                                                "
                                                :value="item.answer_id"
                                            >
                                            </el-option>
                                        </el-select>
                                    </el-form-item>
                                </div>
                            </div>

                            <div
                                class="mx-auto my-4 lg:my-2 flex justify-center"
                            >
                                <el-button
                                    v-if="data.answers.length < maxAns"
                                    @click="pushAns(data.id)"
                                    icon="el-icon-plus"
                                    plain
                                ></el-button>
                            </div>
                        </el-form>
                    </div>
                </div>
                <div class="card-body block" v-if="data.type == 1">
                    <div class="w-full">
                        <p class="text-[15px] font-bold">{{ data.question }}</p>
                        <!-- <input
                            type="text"
                            class="h-[32px] border-none outline-none w-full"
                            placeholder="Nhập câu hỏi..."
                            v-model="dataQuestion[data.id - 1].question"
                        /> -->

                        <div
                            class="w-full mt-2"
                            v-for="(item, index) in data.answers"
                            :key="item.idAns"
                        >
                            <div class="mt-3 flex items-center">
                                <span class="text-[15px] font-semibold"></span>
                                <span class="uppercase mr-2 font-bold"
                                    >{{ alphabet[index] }}:</span
                                >
                                {{ item.text }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4">
                            <div>
                                <span class="text-[13px] font-semibold my-2"
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
                                    :animate="false"
                                    v-model="data.level"
                                    :show-rating="false"
                                    :max-rating="3"
                                    :read-only="true"
                                />
                            </div>
                            <div
                                class="
                                    justify-end
                                    mt-3
                                    ml-auto
                                    flex
                                    items-center
                                "
                            >
                                <span class="text-[13px] font-semibold mr-2"
                                    >Anwser:</span
                                >
                                <span class="uppercase mr-2 font-bold">{{
                                    alphabet[getAlphabet(data)]
                                }}</span>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body hidden" v-if="data.type == 2">
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
                                        message: 'Please enter your question',
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
                                v-for="(item, indexAns) in data.answers"
                                :key="item.id"
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
                                            <el-input v-model="item.text">
                                                <template slot="prepend"
                                                    >{{ indexAns + 1 }}
                                                </template>
                                            </el-input>
                                        </el-form-item>
                                    </el-form>
                                </div>
                            </div>
                            <div class="flex justify-between items-start mt-4">
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
                <div class="card-body block" v-if="data.type == 2">
                    <div class="w-full">
                        <p
                            class="text-[15px] font-bold flex"
                            v-html="customQuestion(data.question)"
                        ></p>

                        <div
                            class="w-full mt-2"
                            v-for="(item, index) in data.answers"
                            :key="item.id"
                        >
                            <div class="mt-3 flex items-center">
                                <span class="text-[15px] font-semibold"></span>
                                <span class="uppercase mr-2 font-bold"
                                    >{{ index + 1 }}:</span
                                >
                                {{ item.text }}
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-4">
                            <div>
                                <span class="text-[13px] font-semibold my-2"
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
                                    :animate="false"
                                    v-model="data.level"
                                    :show-rating="false"
                                    :max-rating="3"
                                    :read-only="true"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <el-pagination
          background
          layout="prev, pager, next"
          :total="total"
          :current-page.sync="current"
          :page-size="perPage"
          @prev-click="paginateClick"
          @next-click="paginateClick"
          @current-change="paginateClick"
        >
        </el-pagination>
        <el-button
            type="primary"
            plain
            class="mx-auto mt-4"
            slot="reference"
            @click="takeMore(5)"
            v-if="take < count"
            >More
        </el-button>
    </div>
</template>

<script>
import baseRequest from "../../../utils/baseRequest";
import StarRating from "vue-star-rating";
import LoadingVue from "../loading/Loading.vue";

export default {
    components: {
        StarRating,
        LoadingVue,
    },
    data() {
        return {
            dataQuestion: [],
            alphabet: ["a", "b", "c", "d", "e", "f", "g", "h"],
            maxAns: 4,
            take: 5,
            count: null,
            isLoading: false,
            rules: {
                name: [
                    {
                        required: true,
                        message: "Please input Activity question",
                        trigger: "blur",
                    },
                ],
            },
            total: 1,
            current: 1,
            pageSize: 1,
            perPage: 1,
        };
    },
    computed: {},
    watch: {
        take() {
            this.getAllData();
        },
    },
    methods: {
        paginateClick(curentPage) {
            this.current = curentPage;
            this.getAllData();
        },
        takeMore(number) {
            this.take = this.take + number;
        },
        pushAns(id) {
            let dataQues = this.dataQuestion.find((item) => item.id == id);
            dataQues.answers.push({
                id: Date.now(),
                question_id: id,
                text: null,
                answer_id: $Helper.randomId(),
            });
        },
        pushQues() {
            this.dataQuestion.push({
                id: Date.now(),
                question: null,
                level: 1,
                dataAns: [
                    {
                        idAns: Date.now() + 123,
                        text: null,
                        alphabet: "A",
                    },
                ],
                answer: null,
            });
        },
        async deleteAns(idQues, idAns) {
            try {
                let res = await baseRequest.post(
                    `/admin/delete-answer-vocabulary`,
                    {
                        id: idAns,
                    }
                );
                let { data } = res;
                if (data.status == 200) {
                    this.getAllData();
                } else {
                    let dataQues = this.dataQuestion.find(
                        (item) => item.id == idQues
                    );
                    dataQues.answers = dataQues.answers.filter(
                        (item) => item.id != idAns
                    );
                }
            } catch (e) {
                let dataQues = this.dataQuestion.find(
                    (item) => item.id == idQues
                );
                dataQues.answers = dataQues.answers.filter(
                    (item) => item.id != idAns
                );
            }
            // let dataQues = this.dataQuestion.find((item) => item.id == idQues);
            // dataQues.dataAns = dataQues.dataAns.filter(
            //     (item) => item.idAns != idAns
            // );
            // let data = dataQues.dataAns;
            // let temp = [];
            // for (let i = 0; i < data.length; i++) {
            //     temp.push({
            //         idAns: data[i].idAns,
            //         text: data[i].text,
            //         alphabet: this.alphabet[i].toUpperCase(),
            //     });
            // }
            // dataQues.dataAns = temp;
        },
        async deleteQues(id) {
            this.$confirm(
                "This will permanently delete the question. Continue?",
                "Warning",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            )
                .then(async () => {
                    try {
                        let res = await baseRequest.post(
                            `/admin/delete-question-vocabulary`,
                            { id: id }
                        );
                        let { data } = res;
                        if (data.status == 200) {
                            this.getAllData();
                            this.$message({
                                type: "success",
                                message: "Delete completed",
                            });
                        }
                    } catch (e) {
                        this.$message({
                            type: "error",
                            message: "Delete error",
                        });
                    }
                })
                .catch(() => {
                    return;
                });
        },
        async getAllData() {
            try {
                this.isLoading = true;
                let { data } = await baseRequest.get(
                    `/admin/list-question-vocabulary?take=${this.take}`
                );
                if (data.status == 200) {
                    setTimeout(() => {
                        this.isLoading = false;
                    }, 1000);
                    this.count = data.count;
                    this.dataQuestion = data.data;
                    // this.total = result.data.data.total;
                    // this.current = result.data.data.current_page;
                    // this.pageSize = result.data.data.last_page;
                    // this.perPage = result.data.data.per_page;
                }
            } catch (error) {
                setTimeout(() => {
                    this.isLoading = false;
                }, 1000);
                console.log(error);
            }
        },
        getAlphabet(data) {
            console.log(data);
            return data.answers.findIndex(
                (item) => item.answer_id == data.right_answers.answer_id
            );
        },
        EditQuestion(id) {
            this.$refs.card[id].children[1].classList.add("block");
            this.$refs.card[id].children[1].classList.remove("hidden");
            this.$refs.card[id].children[2].classList.add("hidden");
            this.$refs.card[id].children[2].classList.remove("block");

            let card_head = this.$refs.card[id].children[0];
            let edit = card_head.querySelector(".edit");
            let save = card_head.querySelector(".save");
            edit.classList.add("hidden");
            edit.classList.remove("block");
            save.classList.add("block");
            save.classList.remove("hidden");
        },
        closeEditQuestion(index) {
            this.$refs.card[index].children[1].classList.add("hidden");
            this.$refs.card[index].children[1].classList.remove("block");
            this.$refs.card[index].children[2].classList.add("block");
            this.$refs.card[index].children[2].classList.remove("hidden");

            let card_head = this.$refs.card[index].children[0];
            let edit = card_head.querySelector(".edit");
            let save = card_head.querySelector(".save");
            edit.classList.add("block");
            edit.classList.remove("hidden");
            save.classList.add("hidden");
            save.classList.remove("block");
        },
        validate(formNameItem, formNameData) {
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
            return isCheck;
        },
        async SaveQuestion(id, index) {
            let isCheck = this.validate("ruleFormData", "ruleFormItem");
            if (isCheck) {
                try {
                    let data = this.dataQuestion.find((item) => item.id == id);
                    let temp = {
                        right_answers: data.right_answers || [],
                        id: data.id || null,
                        question: data.question || "",
                        level: data.level,
                        type: data.type,
                        dataAns: data.answers.map((itemAns) => {
                            return {
                                id: itemAns.answer_id || "",
                                question_id: itemAns.question_id || "",
                                text: itemAns.text || "",
                            };
                        }),
                    };
                    let result = await baseRequest.put(
                        `/admin/update-question-vocabulary`,
                        temp
                    );
                    if (result.data.status == 200) {
                        this.getAllData();
                        this.closeEditQuestion(index);
                        this.$message({
                            type: "success",
                            message: "Edit success",
                        });
                    } else {
                        this.$message({
                            type: "error",
                            message: "Edit error",
                        });
                    }
                    // console.log(result);
                } catch (e) {
                    console.log(e);
                }
            }
        },
        customQuestion(text) {
            if (text) {
                text = text.replaceAll(
                    "#",
                    '<span class="bg-gray-200 w-[20px] px-4 py-2 block mx-2"></span>'
                );
            }
            return text;
        },
        renderAnswer(data, index) {
            if (data.question != null) {
                let sum = 0;
                this.dataQuestion[index].answers = [];
                if (data.question.length > 0) {
                    for (let i = 0; i < data.question.length; i++) {
                        if (data.question[i] === "#") {
                            sum++;
                            let idTemp = $Helper.randomId();
                            this.dataQuestion[index].answers.push({
                                id: Date.now() + i,
                                answer_id: idTemp,
                                text: null,
                                question_id: data.id,
                            });
                        }
                    }
                }
            }
            return;
        },
    },

    created() {
        this.getAllData();
    },
};
</script>
