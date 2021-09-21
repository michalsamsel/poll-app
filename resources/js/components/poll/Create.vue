<template>
  <div class="d-flex justify-content-center">
    <form @submit.prevent="submit">
      <div class="mb-3">
        <label for="question" class="form-label">Question:</label>
        <input
          type="text"
          class="form-control"
          id="question"
          placeholder="Type your question"
          v-model="question"
        />
      </div>
      <div class="mb-1" v-for="(answer, index) in answers" :key="index">
        <label v-bind:for="'answer-' + index" class="form-label"
          >Answer #{{ index + 1 }}</label
        >
        <input
          type="text"
          class="form-control"
          placeholder="Type new answer"
          v-model="answer.content"
          v-bind:id="'answer-' + index"
        />
      </div>
      <button
        type="button"
        class="btn btn-success mx-3 mt-3"
        @click="addAnswer"
        :disabled="answers.length == 10"
      >
        Add answer
      </button>
      <button
        type="button"
        class="btn btn-danger mx-3 mt-3"
        @click="removeAnswer"
        :disabled="answers.length == 2"
      >
        Remove answer
      </button>
      <button type="submit" class="btn btn-primary mx-3 mt-3">
        Create Poll
      </button>
    </form>
  </div>
</template>

<script>
export default {
  name: "pollCreate",
  data() {
    return {
      answers: [
        {
          content: "",
        },
        {
          content: "",
        },
        {
          content: "",
        },
      ],
      answersMinimumAmount: 2,
      answersMaximumAmount: 10,
      question: "",
    };
  },
  methods: {
    addAnswer() {
      if (this.answers.length < this.answersMaximumAmount) {
        this.answers.push({ content: "" });
      }
    },
    removeAnswer() {
      if (this.answers.length > this.answersMinimumAmount) {
        this.answers.pop();
      }
    },
    submit(event) {},
  },
};
</script>