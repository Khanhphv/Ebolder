<template>
  <div class="w-full relative">
    <header-component :user="user" @openDialog="openDialog" />
    <div class="container w-full content relative">
      <div
        class="w-full flex flex-col justify-center items-center gap-4 mt-4 mb-12"
      >
        <h2 class="text-4xl font-bold">Kết quả kiểm tra</h2>
        <div style="align-self: start; font-size: 16px">
          <p class="mb-2">Họ tên: {{ user.name }}</p>
          <p>Email: {{ user.email }}</p>
        </div>
        <div class="overflow-x-scroll w-full">
          <table style="width: 100%">
            <tr>
              <th>Listening</th>
              <th>Speaking</th>
              <th>Reading</th>
              <th>Writing</th>
            </tr>

            <tr>
              <td>
                {{ data.result_listening || "Không thực hiện" }}
              </td>
              <td>
                {{ data.result_speaking || "Không thực hiện" }}
              </td>
              <td>
                {{ data.result_reading || "Không thực hiện" }}
              </td>
              <td>
                {{ data.result_writing || "Không thực hiện" }}
              </td>
            </tr>
            <tr>
              <td colspan="4">
                Finished time:
                {{ completion_time(data.time) }} phút
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <popup-dialog :dialogVisible="dialogVisible" @onClose="dialogVisible = false" />
  </div>
</template>
  <script>
import baseRequest from "../../../utils/baseRequest";

export default {
  props: ["data", "user"],
  data() {
    return {
      dialogVisible: false,
    };
  },
  components: {},
  methods: {
    openDialog() {
      this.dialogVisible = true;
    },
    dongMoPopup(value) {
      this.show = value;
    },
    completion_time(mili) {
      return $Helper.millisToMinutesAndSeconds(mili);
    },
  },
  created() {
    console.log(this.data);
  },
};
</script>
  <style scoped>
.content {
  height: calc(100vh - 90px);
}
.exam {
  border: 1px solid #fff;
  border-radius: 4px;
  padding: 24px;
  text-align: center;
  color: #fff;
  cursor: pointer;
  font-size: 16px;
  transition: all 0.25s linear;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}
.exam:hover {
  background: #fff;
  color: #000;
  transition: all 0.25s linear;
}
.box-content {
  background: linear-gradient(
    96.6deg,
    rgba(0, 115, 121, 0.112) 11.23%,
    rgba(0, 95, 100, 0) 115.9%
  );
  box-sizing: border-box;
}
table,
th,
tr,
td {
  padding: 30px 40px;
  text-align: center;
  font-size: 16px;
}
</style>
  