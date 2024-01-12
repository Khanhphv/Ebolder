<template>
    <div class="w-full">
        <header-component :user="user" @openDialog="openDialog" />
        <div class="w-full mt-[37px] mb-[64px] max-w-[768px] mx-auto">
            <div class="grid grid-cols-2 gap-4">
                <div
                    @click="openLink('Listening')"
                    class="bg-blur-f flex items-center justify-center h-[260px] cursor-pointer hover:shadow-md"
                >
                    <span class="text-[38px] strong font-semibold"> Listening </span>
                </div>
                <div
                    @click="openLink('Vocabulary')"
                    class="bg-blur-f flex items-center justify-center h-[260px] cursor-pointer hover:shadow-md"
                >
                    <span class="text-[38px] strong font-semibold"> Vocabulary </span>
                </div>
                <div
                    @click="openLink('Reading')"
                    class="bg-blur-f flex items-center justify-center h-[260px] cursor-pointer hover:shadow-md"
                >
                    <span class="text-[38px] strong font-semibold"> Reading </span>
                </div>
                <div
                    @click="openLink('Grammar')"
                    class="bg-blur-f flex items-center justify-center h-[260px] cursor-pointer hover:shadow-md"
                >
                    <span class="text-[38px] strong font-semibold"> Grammar </span>
                </div>
            </div>
        </div>
        <footer-component />
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
    props: ["user"],
    methods: {
        openDialog() {
          this.dialogVisible = true;
        },
        openLink(url) {
            window.location.href = `${$Api.baseUrl}/english-level-test/${url}`;
        },
    },
};
</script>
<style></style>
