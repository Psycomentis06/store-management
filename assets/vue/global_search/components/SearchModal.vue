<template>

  <div id="search-modal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
              <a class="nav-link active" id="search-tab" data-toggle="tab" href="#search-tab-content" role="tab"
                 aria-controls="home" aria-selected="true">Search</a>
            </li>
            <li class="nav-item" role="presentation">
              <a class="nav-link" id="global-search-help-tab" data-toggle="tab" href="#global-search-help-tab-content"
                 role="tab" aria-controls="profile" aria-selected="false">Help?</a>
            </li>
          </ul>
          <div class="tab-content" id="globalSearchTabContent">
            <div class="tab-pane fade show active" id="search-tab-content" role="tabpanel" aria-labelledby="search-tab">
              <SearchTab/>
              <hr>
              <SearchResultContainer />
            </div>
            <div class="tab-pane fade" id="global-search-help-tab-content" role="tabpanel"
                 aria-labelledby="global-search-help-tab">
              <HelpTab/>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">
            Full Search
            <i class="la la-external-link"></i>
          </button>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import {defineComponent} from 'vue'
import SearchTab from "./SearchTab.vue";
import HelpTab from "./HelpTab.vue";
import SearchResultContainer from "./SearchResultContainer";

export default defineComponent({
  name: "SearchModal",
  components: {SearchResultContainer, HelpTab, SearchTab},
  methods: {

  }
})
</script>

<style scoped>

</style>