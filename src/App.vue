<template>
  <div>
    <Form @submit="onSubmit" @invalid-submit="onInvalidSubmit" ref="observer">
     <Field name="name" v-slot="{ field, errors }" :rules="validateName">
     <label data-placeholder="*שם הלקוח/עסק/חברה">
     שם מלא:
      <input type="text" v-bind="field" label="Name" v-model="form.full_name" :error-messages="errors" />
      <ErrorMessage name="name" />
      </label>
    </Field>
     <Field name="email" v-slot="{ field, errors }" :rules="validateEmail">
     <label data-placeholder="*אימייל">
     אימייל:
      <input type="email" v-bind="field" label="Email" v-model="form.email" :error-messages="errors" />
      <ErrorMessage name="email" />
      </label>
    </Field>
    <Field name="message" v-slot="{ field, errors }">
    <label data-placeholder="הודעה">
    טקסט חופשי:
    <textarea v-model="form.body" v-bind="field" :error-messages="errors"></textarea>
      <ErrorMessage name="message" />
      </label>
      </Field>
      <button>שליחה</button>
    </Form>
    <div v-if="errors.length">משהו השתבש, אנא נסו שנית.</div>
    <div v-if="submitted">מצוין! ההודעה נשלחה בהצלחה.</div>
  </div>
</template>
<script>
import axios from 'axios';
import { Form, Field, ErrorMessage } from 'vee-validate';
export default {
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  muonted(){
  console.log('mounted');
  },
  data(){
  return{
  url: "https://spite.meidanm.com/wp-json/send-contact-form/v1/contact/",
  form: {
  full_name: '',
  email: '',
  body: '',
  nonce: 'ca25a95594',
  },
  errors: [],
  submitted: false,
  }
  },
  methods: {
    onInvalidSubmit({ errors }) {
const errorFieldName = Object.keys(errors)[0];
document.querySelector('input[name="'+errorFieldName+'"').focus();
    },
    onSubmit(values) {
    document.querySelector('#app form').classList.add('active');
      console.log(values, null, 2);
      axios.post(this.url, this.form)
      .then((response) => {
    document.querySelector('#app form').classList.remove('active');
      console.log(response);
      this.errors = [];
      if(!this.errors.length && response){
      this.submitted = true;
      }
      })
      .catch((error) => {
      this.errors = error.response.data.message;
      })
    },
    validateEmail(value) {
      // if the field is empty
      if (!value) {
        return 'זהו שדה חובה';
      }
      // if the field is not a valid email
      const regex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
      if (!regex.test(value)) {
        return 'האימייל לא תקין, דוגמה לאימייל תקין: example@gmail.com';
      }
      // All is good
      return true;
    }, 
        validateName(value) {
      // if the field is empty
      if (!value) {
        return 'זהו שדה חובה';
      }
      const regex = /^[A-Zאבגדהוזחטיכלמנסעפצקרשתךםףץ._%+-]/i;

      if(value.length < 2){
        return 'חייב להכיל לפחות שתי אותיות';
      }
     if (!regex.test(value)) {
        return 'שדה זה אינו יכול להכיל סימנים מיוחדים או מספרים';
      }
      // All is good
      return true;
    },
      /*  validateMessage(value) {

      // if the field is not a valid email
      const regex = /^[A-Z0-9אבגדהוזחטיכלמנסעפצקרשתךםףץ..()'_%+-]+$/i;
      if (!regex.test(value)) {
        return 'צצ';
      }
      // All is good
      return true;
    },*/
  },
};
</script>