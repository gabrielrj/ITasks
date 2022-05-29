import ajaxCall from "@/services/ajaxCall";

export default {
      async getAllTasks() {
            // eslint-disable-next-line no-useless-catch
            try {
                  let result = null

                  await ajaxCall.callApi('/tasks/all', null, true, 'GET')
                  .then(response => {
                        result = response.data.data.tasks
                  })

                  return result
            }catch (e) {
                  throw e;
            }
      },

      async getAllTasksByUser(userId) {
            // eslint-disable-next-line no-useless-catch
            try {
                  let result = null

                  await ajaxCall.callApi('/tasks/find/user/' + userId, null, true, 'GET')
                  .then(response => {
                        result = response.data.data.tasks
                  })

                  return result
            }catch (e) {
                  throw e;
            }
      },

      async findTaskById(id) {
            // eslint-disable-next-line no-useless-catch
            try {
                  let result = null

                  await ajaxCall.callApi('/tasks/show/' + id, null, true, 'GET')
                  .then(response => {
                        result = response.data.data.task
                  })

                  return result
            }catch (e) {
                  throw e;
            }
      },

      async createTask(payload) {
            // eslint-disable-next-line no-useless-catch
            try {
                  return await ajaxCall.callApi('/tasks/store', payload, true, 'POST')
            }catch (e) {
                  throw e;
            }
      },

      async updateTask(payload) {
            // eslint-disable-next-line no-useless-catch
            try {
                  return await ajaxCall.callApi(`/tasks/update/${payload.uuid}`, payload, true, 'PUT')
            }catch (e) {
                  throw e;
            }
      },

      async deleteTask(id) {
            // eslint-disable-next-line no-useless-catch
            try {
                  return await ajaxCall.callApi(`/tasks/delete/${id}`, null, true, 'DELETE')
            }catch (e) {
                  throw e;
            }
      }
}
