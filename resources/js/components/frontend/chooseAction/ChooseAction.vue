<template>
  <div class="w-full h-[100vh] overflow-y-scroll">
    <header-component :user="user" @openDialog="openDialog" />
    <div class="w-full mt-4">
      <div class="container">
        <div class="flex items-center justify-between flex-col lg:flex-row gap-5">
          <div class="shape shape-learn w-[280px] h-[280px] text-[42px] md:w-[520px] md:h-[520px] md:text-[60px]" @click="openNewPage(1)">Học</div>
          <div class="shape shape-exam w-[280px] h-[280px] text-[42px] md:w-[520px] md:h-[520px] md:text-[60px]" @click="openNewPage(2)">Thi</div>
        </div>
      </div>
    </div>
    <popup-dialog :dialogVisible="dialogVisible" @onClose="dialogVisible = false" />
  </div>
</template>
<script>
export default {
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
    openNewPage(type){
        if(type === 1){
            window.location.href = `${$Api.baseUrl}/learn`;
        }else {
            window.location.href = `${$Api.baseUrl}/exam`;
        }
    }
  },
};
</script>
<style scoped>
>>> .el-timeline-item__timestamp {
  font-size: 16px;
  line-height: 1.2;
}

.shape {
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.shape-learn {
  background: linear-gradient(45deg, #5D6BF8 0%, var(--secondary) 100%);
  animation: morph 8s ease-in-out infinite;
  border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
  transition: all 1s ease-in-out;
  z-index: 5;
}
.shape-exam {
  background: linear-gradient(45deg, #e27fcb 0%, var(--secondary) 100%);
  animation: morph2 8s ease-in-out infinite;
  border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
  transition: all 1s ease-in-out;
  z-index: 5;
}

@keyframes morph {
  0% {
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    background: linear-gradient(
      45deg,
      #5D6BF8 0%,
      var(--secondary) 100%
    );
  }

  50% {
    border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
    background: linear-gradient(45deg, #5D6BF8 0%, var(--secondary) 100%);
  }

  100% {
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    background: linear-gradient(
      45deg,
      #5D6BF8 0%,
      var(--secondary) 100%
    );
  }
}
@keyframes morph2 {
  0% {
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    background: linear-gradient(
      45deg,
      #e27fcb 0%,
      var(--secondary) 100%
    );
  }

  50% {
    border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%;
    background: linear-gradient(45deg, #e27fcb 0%, var(--secondary) 100%);
  }

  100% {
    border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
    background: linear-gradient(
      45deg,
      #e27fcb 0%,
      var(--secondary) 100%
    );
  }
}
</style>
