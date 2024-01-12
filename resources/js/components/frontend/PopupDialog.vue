<template>
  <el-dialog
      title="Pronunciation Links"
      :visible="dialogVisible"
      :show-close="false"
      top="50px"
      width="70%"
  >
    <div class="dialog-container d-flex flex-column" style="gap: 8px; color: #000">
      <span class="font-semibold">1. Luyện âm , luyện đọc tiếng Anh online với Text-to-Speech</span>
      <a style="color: #0d6efd;" href="https://www.nuance.com/omni-channel-customer-engagement/voice-and-ivr/text-to-speech.html" target="_blank">
        https://www.nuance.com/omni-channel-customer-engagement/voice-and-ivr/text-to-speech.html
      </a>
      <span>
        Đọc tiếng Anh online với Text-to-Speech là trang web đọc tiếng Anh online chuẩn nổi tiếng nhất hiện nay, chuyên cung cấp các giọng đọc tiếng Anh chuẩn xác từng câu với 119 giọng đọc khác nhau cho bạn một trải nghiệm tuyệt vời khi đang không biết hoặc muốn học phát âm một từ hay một câu nào đó.
      </span>
      <img :src="nuance" />
      <span>Đọc tiếng Anh online với Text-to-Speech</span>
      <span class="font-semibold">Ưu điểm:</span>
      <ul style="list-style: disc; margin-left: 20px;">
        <li>Cung cấp 119 giọng đọc từ nhiều nước thuộc 53 ngôn ngữ khác nhau.</li>
        <li>Trang web còn cung cấp các giải pháp: IVR hội thoại, trợ lý ảo,...</li>
        <li>Hỗ trợ đọc chính tả và phiên âm nhanh chóng, chính xác.</li>
      </ul>


      <span class="font-semibold">2. Đọc tiếng Anh online chuẩn với NaturalReader</span>
      <a style="color: #0d6efd;" href="https://www.naturalreaders.com/" target="_blank">
        https://www.naturalreaders.com/
      </a>
      <span>
        Đọc tiếng Anh online chuẩn với NaturalReader là trang web đọc tiếng Anh online giọng chuẩn cực kỳ hữu ích hiện nay, cung cấp tính năng đọc tiếng Anh với nhiều sự lựa chọn từ giọng đọc, tốc độ,... cho bạn trải nghiệm tốt nhất, giúp bạn thỏa mái tra cứu phát âm của những từ tiếng Anh mà bạn chưa biết cách phát âm hoặc ngữ điệu,... một cách chuẩn xác nhất.
      </span>
      <img :src="nautral" />
      <span>Đọc tiếng Anh online chuẩn với NaturalReader</span>
      <span class="font-semibold">Ưu điểm:</span>
      <ul style="list-style: disc; margin-left: 20px;">
        <li>Có thể tùy chọn rất nhiều giọng đọc khác nhau.</li>
        <li>Dễ dàng tùy chỉnh tốc độ đọc nhanh chậm tùy theo ý muốn của bạn.</li>
        <li>Có thể chuyển văn bản thành file mp3 và tải về máy nếu muốn.</li>
        <li>Ngoài nhập văn bản trang web còn cho phép đọc các file PDF, TXT, DOC,...</li>
      </ul>


      <span class="font-semibold">3. Phần mềm đọc tiếng Anh chuẩn online - ttsMP3</span>
      <a style="color: #0d6efd;" href="https://ttsmp3.com/" target="_blank">
        https://ttsmp3.com/
      </a>
      <span>
        Phần mềm đọc tiếng Anh chuẩn online - ttsMP3 <br> Phần mềm đọc tiếng Anh chuẩn online - ttsMP3 là trang web đọc câu tiếng Anh giọng chuẩn tuyệt vời mà bạn không thể bỏ qua hiện nay, hỗ trợ chuyển đổi các văn bản tiếng Anh thành giọng nói một cách chuyên nghiệp, hỗ trợ cho các công việc học tập và việc làm của bạn trở nên dễ dàng, hiệu quả hơn.
      </span>
      <img :src="imp3" />
      <span>Phần mềm đọc tiếng Anh chuẩn online - ttsMP3</span>
      <span class="font-semibold">Ưu điểm:</span>
      <ul style="list-style: disc; margin-left: 20px;">
        <li>Có thể tùy chọn hơn 100 giọng đọc khác nhau.</li>
        <li>Dễ dàng tùy chỉnh tốc độ đọc nhanh chậm tùy theo ý muốn của bạn.</li>
        <li>Có thể tải file dưới dạng file mp3 về máy để luyện nghe.</li>
      </ul>


      <span class="font-semibold">4. Web đọc tiếng Anh chuẩn online - ReadSpeaker</span>
      <a style="color: #0d6efd;" href="https://www.readspeaker.com/" target="_blank">
        https://www.readspeaker.com/
      </a>
      <span>
        Web đọc tiếng Anh chuẩn online - ReadSpeaker là trang web đọc tiếng Anh online giọng chuẩn miễn phí tốt nhất hiện nay, hỗ trợ đọc tiếng Anh với nhiều giọng đọc khác nhau, rất thuận tiện để bạn luyện nghe, học phát âm và ngữ điệu một cách dễ dàng, hiệu quả nhất.
      </span>
      <img :src="readspeaker" />
      <span>Web đọc tiếng Anh chuẩn online - ReadSpeaker</span>
      <span class="font-semibold">Ưu điểm:</span>
      <ul style="list-style: disc; margin-left: 20px;">
        <li>Có thể tùy chọn rất nhiều giọng đọc khác nhau.</li>
        <li>Hỗ trợ tua nhanh hoặc chậm các đoạn đọc để nghe dễ dàng.</li>
        <li>Giao diện đơn giản, dễ sử dụng.</li>
      </ul>
      <span class="font-semibold">Nhược điểm:</span>
      <ul style="list-style: disc; margin-left: 20px;">
        <li>Trang web giới hạn số lượng kí tự nhập vào.</li>
        <li>Không thể tải file về máy để luyện nghe được.</li>
      </ul>


      <span class="font-semibold">5. Trang web đọc câu tiếng Anh giọng chuẩn – iSpeech</span>
      <a style="color: #0d6efd;" href="https://www.ispeech.org/" target="_blank">
        https://www.ispeech.org/
      </a>
      <span>
        Trang web đọc câu tiếng Anh giọng chuẩn - iSpeech là website đọc câu tiếng Anh giọng chuẩn miễn phí được nhiều người sử dụng nhất hiện nay, với tính năng chuyên chuyển đổi văn bản, tài liệu thành giọng nói chuẩn, giúp bạn có thể học tập các phát âm tiếng Anh một cách tốt nhất.
      </span>
      <img :src="ispeed" />
      <span class="font-semibold">Ưu điểm:</span>
      <ul style="list-style: disc; margin-left: 20px;">
        <li>Có thể lựa chọn các giọng đọc như Anh - Anh, Anh - Mỹ, Anh - Úc,...</li>
        <li>Có thể tùy chỉnh giọng nam hoặc nữ.</li>
        <li>Có 3 chế độ điều chỉnh tốc độ giọng nói: chậm, đều đặn, nhanh.</li>
        <li>Không giới hạn số lượng ký tự nhập vào.</li>
        <li>Có thể tải file nghe về máy ở các định dạng: WAV, MP3, OGG, WMA, AIFF, ULAW, VOX, MP4.</li>
        <li>Có thể chuyển văn bản, file PDF, sách điện tử thành giọng nói dễ dàng.</li>
      </ul>
    </div>
    <span slot="footer" class="dialog-footer">
      <el-button @click="$emit('onClose')">Close</el-button>
    </span>
  </el-dialog>
</template>

<script>
export default {
  name: 'PopupDialog',
  props: ['dialogVisible'],

  data() {
    return {
      nuance: require('../../../../public/images/popup/nuance.png'),
      readspeaker: require('../../../../public/images/popup/readspeaker.png'),
      nautral: require('../../../../public/images/popup/nautral.png'),
      ispeed: require('../../../../public/images/popup/ispeed.png'),
      imp3: require('../../../../public/images/popup/img.png'),
    }
  }
}
</script>

<style lang="scss" scoped>
.dialog-container {
  height: 700px;
  overflow: auto;
}
</style>
<style lang="scss">
.el-dialog__body {
  padding: 10px 20px;
}
</style>