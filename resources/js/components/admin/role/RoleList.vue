<template>
    <div class="container">
        <LoadingVue v-if="isLoading" />
        <el-table
            :data="
                tableData.filter(
                    (data) =>
                        !search ||
                        data.name.toLowerCase().includes(search.toLowerCase())
                )
            "
            style="width: 97%"
        >
            <el-table-column label="Name" prop="name"> </el-table-column>
            <el-table-column label="Description" prop="display_name">
            </el-table-column>
            <el-table-column align="right">
                <template slot-scope="scope">
                    <el-button
                        size="mini"
                        @click="handleEdit(scope.$index, scope.row)"
                        >Edit
                    </el-button>
                    <el-button
                        size="mini"
                        type="danger"
                        @click="handleDelete(scope.$index, scope.row)"
                        >Delete
                    </el-button>
                </template>
            </el-table-column>
        </el-table>
    </div>
</template>

<script>
import baseRequest from "../../../utils/baseRequest";
import LoadingVue from "../loading/Loading.vue";

export default {
    components: {
        LoadingVue,
    },
    data() {
        return {
            tableData: [
                {
                    id: null,
                    display_name: null,
                    name: null,
                },
            ],
            search: "",
            show: true,
            isLoading: false,
        };
    },
    methods: {
        handleEdit(index, row) {
            window.location.href = `${$Api.baseUrl}/admin/role/edit/` + row.id;
        },
        handleDelete(index, row) {
            this.$confirm(
                "This will permanently delete the file. Continue?",
                "Warning",
                {
                    confirmButtonText: "OK",
                    cancelButtonText: "Cancel",
                    type: "warning",
                }
            )
                .then(async () => {
                    try {
                        let result = await baseRequest.post(
                            `/admin/delete-role-permision/${row.id}`
                        );
                        let { data } = result;
                        if (data.status == 200) {
                            this.getAllRole();
                            this.$message({
                                type: "success",
                                message: "Delete completed",
                            });
                        }
                    } catch (error) {
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
        async getAllRole() {
            try {
                this.isLoading = true;
                let result = await baseRequest.get("/admin/get-all-role");
                let { data } = result;
                if (data.status == 200) {
                    this.isLoading = false;
                    this.tableData = data.data?.map((item) => ({
                        id: item.id,
                        name: item.name,
                        display_name: item.display_name,
                    }));
                }
            } catch (e) {
                setTimeout(() => {
                    this.isLoading = false;
                }, 1000);
                console.log(e);
            }
        },
    },

    created() {
        this.getAllRole();
    },
};
</script>
