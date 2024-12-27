<template>
  <div class="attachment-thumbnail">
    <div v-if="isImage(file.name)">
      <img
        class="attachment-thumbnail-preview js-open-viewer"
        :src="file.path" 
        :alt="file.name"
      />
    </div>
    <div v-else-if="isPDF(file.name)">
      <embed
        class="attachment-thumbnail-preview"
        :src="file.path"
        type="application/pdf"
        width="100%"
        height="400px"
      />
    </div>
    <div v-else>
      <a
        :href="file.path"
        class="attachment-thumbnail-details-title-action dark-hover js-direct-link"
        target="_blank"
        rel="noreferrer nofollow noopener"
      >
        <span class="icon-sm icon-external-link"></span>
        {{ file.name }}
      </a>
    </div>

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
import moment from "moment";

export default {
  name: "File",
  props: ['file'],
  methods: {
    formatDate(dateString) {
      return 'lúc ' + moment(dateString).format('HH:mm:ss') + ' ngày ' + moment(dateString).format('DD-MM-YYYY');
    },
    deleteFile(e) {
      let rect = e.target.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        data: this.file
      };
      this.$emit('openDeleteFile', data);
    },
    editFile(e) {
      let rect = e.target.getBoundingClientRect();
      let data = {
        left: rect.left,
        top: rect.top,
        file: this.file
      };
      this.$emit('showEditFile', data);
    },
    isImage(fileName) {
      const imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];
      const extension = fileName.split('.').pop().toLowerCase();
      return imageExtensions.includes(extension);
    },
    isPDF(fileName) {
      return fileName.split('.').pop().toLowerCase() === 'pdf';
    }
  }
}
</script>

<style scoped lang="scss">
.attachment-thumbnail {
  border-radius: 3px;
  min-height: 80px;
  margin: 0 0 8px;
  overflow: hidden;
  position: relative;
  background-color: rgba(9, 30, 66, .04);

  .attachment-thumbnail-preview {
    background-color: #1c1c1c;
    background-position: 50%;
    background-size: contain;
    background-repeat: no-repeat;
    border-radius: 3px;
    height: 80px;
    margin-top: -40px;
    position: absolute;
    top: 50%;
    left: 0;
    text-align: center;
    text-decoration: none;
    z-index: 1;
    width: 112px;
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
    a:hover{
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