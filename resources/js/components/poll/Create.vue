<template>
  <div class="d-flex justify-content-center">
    <form @submit.prevent="submit">
      <div class="input-group mb-3">
        <label for="question" class="form-label">Question:</label>
        <input
          type="text"
          class="form-control input-group"
          id="question"
          placeholder="Type your question"
          v-model="question"
          required
          maxlength="255"
        />
      </div>
      <div class="mb-1" v-for="(answer, index) in answers" :key="index">
        <label v-bind:for="'answer-' + index" class="form-label"
          >Answer #{{ index + 1 }}</label
        >
        <input
          type="text"
          class="input-group form-control"
          v-bind:id="'answer-' + index"
          placeholder="Type new answer"
          v-model="answer.content"
          required
          maxlength="255"
        />
      </div>
      <div class="form-check">
        <input
          type="checkbox"
          class="form-check-input"
          id="multiplyAnswers"
          v-model="multiplyAnswers"
        />
        <label
          class="form-check-label"
          for="multiplyAnswers"
          v-if="multiplyAnswers"
        >
          Allow to choose multiply answers in poll.
        </label>
        <label class="form-check-label" for="multiplyAnswers" v-else>
          Allow to choose single answer in poll.
        </label>
        <br />
        <input
          type="checkbox"
          class="form-check-input"
          id="duplicateAnswers"
          v-model="duplicateAnswers"
        />
        <label
          class="form-check-label"
          for="duplicateAnswers"
          v-if="duplicateAnswers"
        >
          User can answer more then once.
        </label>
        <label class="form-check-label" for="duplicateAnswers" v-else>
          User can answer only once.
        </label>
      </div>
      <div
        class="alert alert-danger mt-3"
        role="alert"
        v-if="errorFormValidation"
      >
        {{ errorFormValidation }}
      </div>
      <div class="mt-3">
        <button
          type="button"
          class="btn btn-success mx-3"
          @click="addAnswer"
          :disabled="answers.length == 10"
        >
          Add answer
        </button>
        <button
          type="button"
          class="btn btn-danger mx-3"
          @click="removeAnswer"
          :disabled="answers.length == 2"
        >
          Remove answer
        </button>
        <button type="submit" class="btn btn-primary mx-3">Create Poll</button>
      </div>
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
      duplicateAnswers: false,
      multiplyAnswers: false,
      errorFormValidation: "",
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
    validateForm() {
      if (!this.question) {
        this.errorFormValidation = "Question can't be empty.";
        return false;
      } else if (this.question.length > 255) {
        this.errorFormValidation = `Question is ${
          this.question.length - 255
        } over character limit.`;
        return false;
      }
      for (let i = 0; i < this.answers.length; i++) {
        if (!this.answers[i].content) {
          this.errorFormValidation = `Answer ${i + 1} can't be empty.`;
          return false;
        } else if (this.answers[i].content.length > 255) {
          this.errorFormValidation = `Answer ${i + 1} is ${
            this.question.length - 255
          } over character limit.`;
          return false;
        }
      }
      this.errorFormValidation = "";
      return true;
    },
    submit(event) {    
      console.log("submit");
      if (this.validateForm()) {
        console.log("validation");
        this.$axios
          .post("/api/poll/create", {
            question: this.question,
            answers: this.answers,
            multiplyAnswers: this.multiplyAnswers,
            duplicateAnswers: this.duplicateAnswers
          })
          .then((response) => {})
          .catch((error) => {});
      }
    },
  },
};
</script>