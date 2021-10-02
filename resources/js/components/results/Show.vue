<template>
  <div class="row justify-content-center">
    <div class="col-4" v-if="pollResults.length">
      <h3>{{ question }}: (Total Votes: {{pollTotalVotes}})</h3>      
      <div class="my-3" v-for="pollResult in pollResults" :key="pollResult.id">
        <h5>{{ pollResult.content }} ({{pollResult.resultCount}} Votes)</h5>          
        <div class="progress">
          <div
            class="progress-bar"
            role="progressbar"
            v-bind:style="`width: ${getPercentage(pollResult.resultCount)}%`"
            v-bind:aria-valuenow="getPercentage(pollResult.resultCount)"
            aria-valuemin="0"
            aria-valuemax="100"
          >
          {{getPercentage(pollResult.resultCount)}}%
          </div>
        </div>
      </div>
    </div>
    <div class="col-4" v-else>
      <h1>Loading poll data</h1>
    </div>
  </div>
</template>

<script>
export default {
  name: "resultShow",
  data() {
    return {
      question: "",
      pollResults: null,
      pollTotalVotes: 0,
      errorMessage: "",
    };
  },
  methods: {
    getPercentage(pollPartVotes) {
      return (((pollPartVotes * 1) / this.pollTotalVotes) * 100).toFixed(2);
    },
  },
  created() {
    this.$axios
      .get(`/api/poll/${this.$route.params.id}/result`, {})
      .then((response) => {
        this.pollResults = response.data.pollResults;
        this.question = response.data.question.content;
        for (let i = 0; i < this.pollResults.length; i++) {
          this.pollTotalVotes += this.pollResults[i].resultCount;
        }
        console.log(this.pollResults.length);
      })
      .catch((error) => {
        this.$router.push({
          name: "home",
        });
      });
  },
};
</script>