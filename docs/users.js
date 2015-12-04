/**
 * @apiDefine UserParams
 * 
 * @apiParam {String} first_name First name
 * @apiParam {String} last_name Last name
 * @apiParam {String} email Email address
 * @apiParam {String} password Password
 */

/**
 * @api {GET} /users List all users.
 * @apiVersion 1.0.0
 * @apiGroup User
 */

/**
 * @api {POST} /users Create a new user.
 * @apiVersion 1.0.0
 * @apiGroup User
 *
 * @apiUse UserParams
 * @apiSampleRequest off
 */

/**
 * @api {GET} /users/:id Get the user with an id
 * @apiVersion 1.0.0
 * @apiGroup User
 *
 * @apiSampleRequest /users/1
 */

/**
 * @api {PUT} /users/:id Update the user with an id.
 * @apiVersion 1.0.0
 * @apiGroup User
 *
 * @apiUse UserParams
 * @apiSampleRequest off
 */