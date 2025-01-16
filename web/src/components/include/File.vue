<template>
<div class="file-container">
    <!-- Kiểm tra xem tệp là hình ảnh, PDF hay loại tệp khác -->
    <div v-if="isImage(file.name)">
        <!-- Bọc ảnh trong thẻ <a> để chuyển hướng khi nhấn -->
        <a :href="getFullUrl(file.path)" target="_blank">
            <img class="attachment-thumbnail-preview js-open-viewer" :src="getFullUrl(file.path)" :alt="file.name" ref="image" @wheel="handleZoom" :style="{ transform: 'scale(' + zoomLevel + ')', cursor: 'pointer' }" />
        </a>
    </div>
    <div v-else-if="isPDF(file.name)">
        <embed class="attachment-thumbnail-preview" :src="getFullUrl(file.path)" type="application/pdf" width="100%" height="400px" />
    </div>
    <div v-else>
        <a :href="getFullUrl(file.path)" class="attachment-thumbnail-details-title-action dark-hover js-direct-link" target="_blank" rel="noreferrer nofollow noopener" @click.prevent="downloadFile">
            <span class="icon-sm icon-external-link"></span>
            {{ file.name }}
        </a>
    </div>

    <!-- Thông tin chi tiết và hành động -->
    <p class="attachment-thumbnail-details js-open-viewer">
        <span class="attachment-thumbnail-name">{{ file.name }}</span>
        <span class="u-block quiet attachment-thumbnail-details-title-options">
            <span>Đã thêm <span class="date past" dt="2021-03-09T14:59:33.508Z" title="9 tháng 3 năm 2021 21:59">
                    {{ formatDate(file.created_at) }}
                </span></span>
            <span>
                <a class="attachment-thumbnail-details-title-options-item dark-hover js-confirm-delete" href="#">
                    <span class="attachment-thumbnail-details-options-item-text" @click="deleteFile">Xoá</span>
                </a>
            </span>
            -
            <span>
                <a class="attachment-thumbnail-details-title-options-item dark-hover js-edit" @click="editFile" href="#">
                    <span class="attachment-thumbnail-details-options-item-text">Chỉnh sửa</span>
                </a>
            </span>
        </span>
    </p>
</div>
</template>

<script>
export default {
    name: "File",
    props: ['file'],
    data() {
        return {
            zoomLevel: 1 // Khoảng zoom của ảnh, bạn có thể thay đổi
        }
    },
    methods: {
        formatDate(dateString) {
            const date = new Date(dateString);
            const hours = String(date.getHours()).padStart(2, '0');
            const minutes = String(date.getMinutes()).padStart(2, '0');
            const seconds = String(date.getSeconds()).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const year = date.getFullYear();

            return `lúc ${hours}:${minutes}:${seconds} ngày ${day}-${month}-${year}`;
        },

        deleteFile(e) {
            let rect = e.target.getBoundingClientRect();
            let data = {
                left: rect.left,
                top: rect.top,
                data: this.file
            };
            this.$emit('openDeleteFile', data); // Phát sự kiện với dữ liệu xóa tệp
        },

        editFile(e) {
            let rect = e.target.getBoundingClientRect();
            let data = {
                left: rect.left,
                top: rect.top,
                file: this.file
            };
            this.$emit('showEditFile', data); // Phát sự kiện với dữ liệu chỉnh sửa tệp
        },

        isImage(fileName) {
            const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
            const extension = fileName.split('.').pop().toLowerCase();
            return imageExtensions.includes(extension);
        },

        isPDF(fileName) {
            return fileName.split('.').pop().toLowerCase() === 'pdf';
        },

        // Phương thức tải tệp về
        downloadFile() {
            if (!this.file || !this.file.path) {
                console.error('Đường dẫn tệp không hợp lệ');
                return;
            }

            const link = document.createElement('a');
            link.href = this.getFullUrl(this.file.path); // Tạo URL đầy đủ
            link.download = this.file.name || 'file'; // Tên file khi tải về
            link.click(); // Kích hoạt sự kiện click để tải tệp
        },

        // Tạo URL đầy đủ cho tệp
        getFullUrl(path) {
            const baseUrl = 'http://127.0.0.1:8000/storage/'; // Thay thế với URL thực tế của bạn
            return baseUrl + path;
        },

        // Xử lý sự kiện zoom ảnh
        handleZoom(event) {
            const zoomStep = 0.1;
            if (event.deltaY < 0) {
                this.zoomLevel += zoomStep; // Zoom in
            } else {
                this.zoomLevel -= zoomStep; // Zoom out
            }
            this.zoomLevel = Math.max(1, Math.min(this.zoomLevel, 3)); // Giới hạn zoom
        }
    }
}
</script>

<style lang="scss" scoped>
.file-container {
    border-radius: 3px;
    min-height: 80px;
    margin: 0 0 8px;
    overflow: hidden;
    position: relative;
    background-color: rgba(9, 30, 66, .04);

    img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        cursor: pointer;
    }

    .attachment-thumbnail-preview {
        background-color: #1c1c1c;
        background-position: 50%;
        background-size: contain;
        background-repeat: no-repeat;
        border-radius: 3px;
        width: 112px;
        height: 80px;
        margin-top: -40px;
        position: absolute;
        top: 50%;
        left: 0;
        text-align: center;
        text-decoration: none;
        z-index: 1;
        color: #172b4d;
    }

    .attachment-thumbnail-details {
        box-sizing: border-box;
        cursor: pointer;
        padding: 8px 8px 8px 128px;
        min-height: 80px;
        margin: 0;
        z-index: 0;

        .attachment-thumbnail-name {
            font-weight: 700;
        }

        a {
            color: #172b4d;
            padding-left: 3px;
        }

        a:hover {
            text-decoration: solid;
        }

        .attachment-thumbnail-details-title-options {
            margin-bottom: 8px;
            display: block;
            cursor: pointer;

            span {
                color: #5e6c84;
            }
        }
    }
}
</style>
