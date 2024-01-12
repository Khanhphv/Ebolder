<template>
    <div class="w-full h-[100vh] overflow-y-scroll">
      <header-component :user="user" @openDialog="openDialog" />
      <div class="w-full mt-4">
        <div class="container">
          <div class="block" v-if="data.length > 0">
            <el-timeline>
              <el-timeline-item
                v-for="(item, index) in data"
                :key="index"
                type="success"
                size="large"
                :timestamp="created_at(item.created_at)"
                placement="top"
              >
                <el-card shadow="never" class="p-4">
                  <el-row :gutter="20">
                    <el-col :xs="24" :span="6">
                      <div class="my-2">
                        <el-statistic title="Test name">
                          <template slot="formatter">
                            {{ item.topic_name }}
                          </template>
                        </el-statistic>
                      </div>
                    </el-col>
                    <el-col :xs="24" :span="6">
                      <div class="my-2">
                        <el-statistic title="Score">
                          <template slot="formatter">
                            <span class="text-green-500">{{
                              item.scores
                            }}</span>
                          </template>
                          <template slot="suffix">
                            <span class="like">
                              <i class="el-icon-trophy" style="color: green"></i>
                            </span>
                          </template>
                        </el-statistic>
                      </div>
                    </el-col>
                    <el-col :xs="24" :span="6">
                      <div class="my-2">
                        <el-statistic title="Type">
                          <template slot="formatter">
                            <span class="text-green-500">{{
                              item.test_type
                            }}</span>
                          </template>
                          <template slot="suffix">
                            <span class="like">
                              <i class="el-icon-trophy" style="color: green"></i>
                            </span>
                          </template>
                        </el-statistic>
                      </div>
                    </el-col>
                    <el-col :xs="24" :span="6">
                      <div class="my-2">
                        <el-statistic title="Finished time">
                          <template slot="formatter">
                            {{ completion_time(item.completion_time) }}
                          </template>
                          <template slot="suffix">
                            <span class="like">
                              <i class="el-icon-timer" style="color: red"></i>
                            </span>
                          </template>
                        </el-statistic>
                      </div>
                    </el-col>
                  </el-row>
                </el-card>
              </el-timeline-item>
            </el-timeline>
          </div>
          <div v-else>
            <el-card shadow="never" class="p-4">
              <el-empty description="History"></el-empty>
            </el-card>
          </div>
        </div>
      </div>
      <popup-dialog :dialogVisible="dialogVisible" @onClose="dialogVisible = false" />
    </div>
  </template>
  <script>
  export default {
    props: ["data", "user"],
    data() {
      return {
        dialogVisible: false,
      };
    },
    computed: {},
    methods: {
      openDialog() {
        this.dialogVisible = true;
      },
      completion_time(mili) {
        return $Helper.millisToMinutesAndSeconds(mili);
      },
      created_at(time) {
        return $Helper.formatDateTime(time);
      },
    },
    created() {
      console.log(this.data);
    },
  };
  </script>
  <style scoped>
  >>> .el-timeline-item__timestamp {
    font-size: 16px;
    line-height: 1.2;
  }
  </style>
  