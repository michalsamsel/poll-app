<template>
  <div class="row justify-content-center">
    <div class="col-4">
      <h1>{{ question }}</h1>
      <form @submit.prevent="submit">
        <div v-if="multiplyAnswers">
          <!--Multi answer poll variant.-->
          <div class="form-check" v-for="answer in answers" :key="answer.id">
            <input
              type="checkbox"
              v-bind:id="answer.id"
              v-bind:value="answer.id"
              v-model="checkboxAnswers"
            />
            <label v-bind:for="answer.id">{{ answer.content }}</label>
          </div>
        </div>
        <div v-else>
          <!--Single answer poll variant.-->
          <div class="form-check" v-for="answer in answers" :key="answer.id">
            <input
              type="radio"
              v-bind:id="answer.id"
              v-bind:value="answer.id"
              v-model="radioAnswers"
            />
            <label v-bind:for="answer.id">{{ answer.content }}</label>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Send answer</button>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  name: "pollShow",
  data() {
    return {
      question: "",
      answers: [],
      duplicateAnswers: null,
      multiplyAnswers: null,
      radioAnswers: "",
      checkboxAnswers: [],
    };
  },
  methods: {
    submit(event) {},
  },
  created() {
    this.$axios
      .get(`/api/poll/${this.$route.params.id}`, {})
      .then((response) => {
        this.question = response.data.poll.content;
        this.answers = response.data.poll.answers;
        this.duplicateAnswers = response.data.poll.duplicate_answers;
        this.multiplyAnswers = response.data.poll.multiply_answers;
      })
      .catch((error) => {
        this.$router.push({
          name: "home",
        });
      });
  },
};
</script>