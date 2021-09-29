<template>
  <div class="row justify-content-center">
    <div class="col-4" v-if="answers.length">
      <h1>{{ question }}:</h1>
      <form @submit.prevent="submit">
        <div v-if="manyAnswers">
          <!--Multi answer poll variant.-->
          <div class="form-check" v-for="answer in answers" :key="answer.id">
            <input
              type="checkbox"
              v-bind:id="answer.id"
              v-bind:value="answer.id"
              v-model="pollResult"
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
              v-model="pollResult"
            />
            <label v-bind:for="answer.id">{{ answer.content }}</label>
          </div>
        </div>
        <div class="alert alert-danger mt-3" role="alert" v-if="errorMessage">
          {{ errorMessage }}
        </div>
        <button type="submit" class="btn btn-primary">Send answer</button>
        <button
          type="button"
          class="btn btn-success mx-3"
          @click="showResult()"
        >
          Show Results
        </button>
      </form>
    </div>
    <div class="col-4" v-else>
      <h1>Loading poll data</h1>
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
      manyAnswers: null,
      pollResult: [],
      errorMessage: "",
    };
  },
  methods: {
    submit(event) {
      this.$axios
        .post(`/api/poll/${this.$route.params.id}/result/create`, {
          result: this.pollResult,
        })
        .then((response) => {
          if (response.status === 200) {
            this.showResult();
          }
        })
        .catch((error) => {
          this.errorMessage = error.response.data.message;
        });
    },
    showResult() {
      this.$router.push({
        name: "resultShow",
        params: { id: this.$route.params.id },
      });
    },
  },
  created() {
    this.$axios
      .get(`/api/poll/${this.$route.params.id}`, {})
      .then((response) => {
        this.question = response.data.poll.content;
        this.answers = response.data.poll.answers;
        this.duplicateAnswers = response.data.poll.duplicate_answers;
        this.manyAnswers = response.data.poll.many_answers;
      })
      .catch((error) => {
        this.$router.push({
          name: "home",
        });
      });
  },
};
</script>