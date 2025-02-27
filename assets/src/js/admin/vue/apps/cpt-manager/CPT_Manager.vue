<template>
  <div class="atbdp-cpt-manager cptm-p-20">
    <!-- atbdp-cptm-header -->
    <div class="atbdp-cptm-header">
      <component
        v-if="options.name && options.name.type"
        :is="options.name.type + '-field'"
        v-bind="options.name"
        @update="updateOptionsField({ field: 'name', value: $event })"
      />

      <headerNavigation />
    </div>

    <!-- atbdp-cptm-body -->
    <div class="atbdp-cptm-body">
      <tabContents />

      <div class="atbdp-cptm-status-feedback" v-if="status_messages.length">
        <div
          class="cptm-alert"
          :class="'cptm-alert-' + status.type"
          v-for="(status, index) in status_messages"
          :key="index"
        >
          {{ status.message }}
        </div>
      </div>
    </div>

    <div class="atbdp-cptm-footer">
      <div class="atbdp-cptm-progress-bar"></div>
      <div class="atbdp-cptm-footer-actions">
        <button
          type="button"
          :disabled="footer_actions.save.isDisabled"
          class="cptm-btn cptm-btn-primary"
          @click="saveData()"
        >
          <span
            v-if="footer_actions.save.showLoading"
            class="fa fa-spinner fa-spin"
          ></span>
          {{ footer_actions.save.label }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import { mapGetters } from "vuex";
import headerNavigation from "./Header_Navigation.vue";
import tabContents from "./TabContents.vue";

const axios = require("axios").default;

export default {
  name: "cpt-manager",

  components: {
    headerNavigation,
    tabContents
  },

  computed: {
    ...mapState({
      options: "options"
    })
  },

  created() {
    if (this.$root.fields) {
      this.$store.commit("updateFields", this.$root.fields);
    }

    if (this.$root.layouts) {
      this.$store.commit("updatelayouts", this.$root.layouts);
    }

    if (this.$root.options) {
      this.$store.commit("updateOptions", this.$root.options);
    }

    // console.log( this.options );

    if (this.$root.config) {
      this.$store.commit("updateConfig", this.$root.config);
    }

    if (this.$root.id && !isNaN(this.$root.id)) {
      const id = parseInt(this.$root.id);

      if (id > 0) {
        this.listing_type_id = id;
        this.footer_actions.save.label = "Update";
      }
    }
  },

  data() {
    return {
      listing_type_id: null,
      status_messages: [],
      footer_actions: {
        save: {
          show: true,
          label: "Create",
          showLoading: false,
          isDisabled: false
        }
      }
    };
  },

  methods: {
    ...mapGetters(["getFieldsValue"]),

    updateOptionsField(payload) {
      if (!payload.field) {
        return;
      }
      if (typeof payload.value === "undefined") {
        return;
      }

      this.$store.commit("updateOptionsField", payload);
    },

    updateData() {
      let fields = this.getFieldsValue();

      let submission_url = this.$store.state.config.submission.url;
      let submission_with = this.$store.state.config.submission.with;

      let form_data = new FormData();

      if (submission_with && typeof submission_with === "object") {
        for (let data_key in submission_with) {
          form_data.append(data_key, submission_with[data_key]);
        }
      }

      if (this.listing_type_id) {
        form_data.append("listing_type_id", this.listing_type_id);
        this.footer_actions.save.label = "Update";
      }

      for (let field_key in fields) {
        let value = this.maybeJSON(fields[data_key]);
        form_data.append(data_key, value);
      }

      console.log({ submission_url, submission_with });
    },

    saveData() {
      let options = this.$store.state.options;
      let fields = this.$store.state.fields;

      let submission_url = this.$store.state.config.submission.url;
      let submission_with = this.$store.state.config.submission.with;

      let form_data = new FormData();

      if (submission_with && typeof submission_with === "object") {
        for (let data_key in submission_with) {
          form_data.append(data_key, submission_with[data_key]);
        }
      }

      if (this.listing_type_id) {
        form_data.append("listing_type_id", this.listing_type_id);
        this.footer_actions.save.label = "Update";
      }

      // Get Options Fields Data
      let options_field_list = [];
      for (let field in options) {
        let value = this.maybeJSON(options[field].value);

        form_data.append(field, value);
        options_field_list.push(field);
      }

      form_data.append("field_list", JSON.stringify(field_list));

      // Get Form Fields Data
      let field_list = [];
      for (let field in fields) {
        let value = this.maybeJSON([fields[field].value]);

        form_data.append(field, value);
        field_list.push(field);
      }

      form_data.append("field_list", this.maybeJSON(field_list));

      this.status_messages = [];
      this.footer_actions.save.showLoading = true;
      this.footer_actions.save.isDisabled = true;
      const self = this;

      // return;
      axios
        .post(submission_url, form_data)
        .then(response => {
          self.footer_actions.save.showLoading = false;
          self.footer_actions.save.isDisabled = false;

          // console.log( response );
          // return;

          if (response.data.term_id && !isNaN(response.data.term_id)) {
            self.listing_type_id = response.data.term_id;
            self.footer_actions.save.label = "Update";
            self.listing_type_id = response.data.term_id;

            if (response.data.redirect_url) {
              window.location = response.data.redirect_url;
            }
          }

          if (response.data.status && response.data.status.status_log) {
            for (let status_key in response.data.status.status_log) {
              self.status_messages.push({
                type: response.data.status.status_log[status_key].type,
                message: response.data.status.status_log[status_key].message
              });
            }

            setTimeout(function() {
              self.status_messages = [];
            }, 5000);
          }

          // console.log( response );
        })
        .catch(error => {
          self.footer_actions.save.showLoading = false;
          self.footer_actions.save.isDisabled = false;
          console.log(error);
        });
    },

    maybeJSON(data) {
      let value = typeof data === "undefined" ? "" : data;

      if (
        ("object" === typeof value && Object.keys(value)) ||
        Array.isArray(value)
      ) {
        let json_encoded_value = JSON.stringify(value);
        let base64_encoded_value = this.encodeUnicodedToBase64(
          json_encoded_value
        );
        value = base64_encoded_value;
      }

      return value;
    },

    encodeUnicodedToBase64(str) {
      // first we use encodeURIComponent to get percent-encoded UTF-8,
      // then we convert the percent encodings into raw bytes which
      // can be fed into btoa.
      return btoa(
        encodeURIComponent(str).replace(
          /%([0-9A-F]{2})/g,
          function toSolidBytes(match, p1) {
            return String.fromCharCode("0x" + p1);
          }
        )
      );
    }
  }
};
</script>
