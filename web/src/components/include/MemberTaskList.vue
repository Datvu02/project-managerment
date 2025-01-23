<template>
<div ref="modalContainer" @click="handleOutsideClick" style="position: fixed; top: 0; left: 0; z-index: 3000; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: flex; justify-content: center; align-items: center;">

    <!-- Thêm nút đóng ở góc trên bên phải -->
    <button @click="closeModal" style="position: absolute; top: 10px; right: 10px; background-color: red; color: white; border: none; padding: 10px; border-radius: 5px; cursor: pointer;">
        Đóng
    </button>

    <!-- Element UI Table -->
    <el-table :data="tasks" style="width: 50%; height: 80%; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); background-color: #fff;">

        <!-- Table Header -->
        <template slot="header">
            <h2>Thống kê công việc của: {{ getUserName(userId) }}</h2>
        </template>

        <!-- Table Body -->
        <el-table-column label="Công việc" prop="title"></el-table-column>
        <el-table-column label="Deadline công việc" prop="deadline"></el-table-column>

        <!-- Cột Trạng thái công việc với điều kiện hiển thị -->
        <el-table-column label="Trạng thái công việc" :formatter="statusFormatter"></el-table-column>

        <el-table-column label="Thời gian hoàn thành" prop="completion_deadline"></el-table-column>
        <el-table-column label="Trạng thái hoàn thành công việc" :formatter="statusDeadlineFormatter"></el-table-column>

    </el-table>
</div>
</template>

  
<script>
import api from "../../api"; // Đảm bảo import api đúng

export default {
    props: {
        projectId: {
            type: Number,
            required: true,
        },
        userId: {
            type: Number,
            required: true,
        },
        projectAnalytic: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            tasks: [], // Mảng chứa danh sách công việc
        };
    },
    methods: {
        // Hàm để lấy tên người dùng từ projectAnalytic
        getUserName(userId) {
            const user = this.projectAnalytic.users.find((user) => user.id === userId);
            return user ? user.name : "";
        },

        // Hàm gọi API để lấy công việc của người dùng trong dự án
        async getTasksByProject() {
            try {
                const response = await api.getAllCardByUserInProject(this.projectId, this.userId);
                // Lưu dữ liệu công việc vào tasks
                this.tasks = response.data.data || [];
            } catch (error) {
                console.error("Error fetching tasks:", error);
                this.tasks = []; // Nếu có lỗi thì reset mảng tasks
            }
        },

        // Hàm để hiển thị trạng thái công việc
        statusFormatter(row) {
            if (row.status === 0) {
                return 'Đang làm';
            }
            if (row.status === 1) {
                return 'Sắp hết hạn';
            }

            if (row.status === 2) {
                return 'Quá hạn';
            }
            if (row.status === 3) {
                return 'Hoàn thành';
            }
        },

        // Hàm để hiển thị trạng thái hoàn thành công việc
        statusDeadlineFormatter(row) {
            if (row.completion_status === 0 || row.completion_status === 3) {
                return 'Đang làm';
            }
            if (row.completion_status === 1) {
                return 'Đã nộp';
            }
            if (row.completion_status === 2) {
                return 'Hoàn thành';
            }
        },

        // Hàm đóng modal khi click nút đóng
        closeModal() {
            this.$emit('close'); // Gửi sự kiện 'close' đến component cha để đóng modal
        },

        // Hàm xử lý khi click vào bên ngoài bảng
        handleOutsideClick(event) {
            // Kiểm tra xem người dùng có click bên ngoài bảng không
            if (!this.$refs.modalContainer.contains(event.target)) {
                // Tắt component (ẩn bảng) bằng cách emit sự kiện hoặc update giá trị state
                this.$emit('close'); // Gửi sự kiện 'close' đến component cha để đóng modal
            }
        },
    },
    mounted() {
        // Khi component được mount, gọi API để lấy danh sách công việc
        this.getTasksByProject();
    },
};
</script>
