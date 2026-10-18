<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CountyController;
use App\Http\Controllers\CityController;

//==== County ====

/**
 * @api {get} /counties List counties
 * @apiName GetCounties
 * @apiGroup County
 * @apiDescription Returns all counties ordered by name, with the summed population of their cities.
 *
 * @apiQuery {String} [search] Filters by county name or badge (partial match).
 *
 * @apiSuccess {Object[]} counties List of counties.
 * @apiSuccess {Number} counties.id County id.
 * @apiSuccess {String} counties.name County name.
 * @apiSuccess {String} counties.badge County badge.
 * @apiSuccess {Number} counties.total_population Sum of the population of the county's cities (0 if none).
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "counties": [
 *         { "id": 1, "name": "Pest", "badge": "PE", "total_population": 1250000 }
 *       ]
 *     }
 */
Route::get('/counties', [CountyController::class, 'index'])->name('counties.index');

/**
 * @api {post} /county Create county
 * @apiName StoreCounty
 * @apiGroup County
 *
 * @apiBody {String} name County name.
 * @apiBody {String} badge County badge.
 *
 * @apiSuccess (201) {String} message Result message.
 * @apiSuccess (201) {Object} data The created county.
 *
 * @apiError (422) ValidationError One or more fields are missing or invalid.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 201 Created
 *     { "message": "County created.", "data": { "id": 2, "name": "Fejér", "badge": "FE" } }
 *
 * @apiErrorExample {json} Validation-Error:
 *     HTTP/1.1 422 Unprocessable Entity
 *     { "message": "The name field is required.", "errors": { "name": ["The name field is required."] } }
 */
Route::post('/county', [CountyController::class, 'store'])->name('counties.store');

/**
 * @api {delete} /county/:id Delete county
 * @apiName DestroyCounty
 * @apiGroup County
 * @apiDescription Deletes the county if it exists; succeeds silently otherwise.
 *
 * @apiParam {Number} id County id.
 *
 * @apiSuccess {String} message Result message.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     { "message": "County deleted." }
 */
Route::delete('/county/{id}', [CountyController::class, 'destroy'])->name('counties.destroy');

/**
 * @api {patch} /county/:id Update county
 * @apiName UpdateCounty
 * @apiGroup County
 *
 * @apiParam {Number} id County id.
 * @apiBody {String} name County name.
 * @apiBody {String} badge County badge.
 *
 * @apiSuccess {String} message Result message.
 * @apiSuccess {Object} data The updated county.
 *
 * @apiError (404) NotFound County does not exist.
 * @apiError (422) ValidationError One or more fields are missing or invalid.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     { "message": "County updated.", "data": { "id": 1, "name": "Pest", "badge": "PE" } }
 */
Route::patch('/county/{id}', [CountyController::class, 'update'])->name('counties.update');

//==== City ====

/**
 * @api {get} /cities List cities
 * @apiName GetCities
 * @apiGroup City
 * @apiDescription Returns cities ordered by name, 15 per page (Laravel paginator format).
 *
 * @apiQuery {String} [search] Filters by city name or zip code (partial match).
 * @apiQuery {Number} [county_id] Filters by county id.
 * @apiQuery {Number} [page=1] Page number.
 *
 * @apiSuccess {Object} cities Paginated cities.
 * @apiSuccess {Object[]} cities.data City rows.
 * @apiSuccess {Number} cities.data.id City id.
 * @apiSuccess {Number} cities.data.zip_code Zip code.
 * @apiSuccess {String} cities.data.city City name.
 * @apiSuccess {Number} cities.data.population Population.
 * @apiSuccess {Number} cities.data.id_county County id.
 * @apiSuccess {String} cities.data.county_name County name.
 * @apiSuccess {Number} cities.current_page Current page.
 * @apiSuccess {Number} cities.last_page Last page.
 * @apiSuccess {Number} cities.per_page Items per page.
 * @apiSuccess {Number} cities.total Total number of matches.
 * @apiSuccess {Object[]} counties All counties ordered by name (for filter dropdowns).
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "cities": {
 *         "current_page": 1,
 *         "data": [
 *           { "id": 1, "zip_code": 1011, "city": "Budapest", "population": 1700000, "id_county": 1, "county_name": "Pest" }
 *         ],
 *         "per_page": 15, "last_page": 1, "total": 1
 *       },
 *       "counties": [ { "id": 1, "name": "Pest", "badge": "PE" } ]
 *     }
 */
Route::get('/cities', [CityController::class, 'index'])->name('cities.index');

/**
 * @api {post} /city Create city
 * @apiName StoreCity
 * @apiGroup City
 *
 * @apiBody {Number} zip_code Zip code.
 * @apiBody {String} city City name.
 * @apiBody {Number} population Population.
 * @apiBody {Number} id_county County id.
 *
 * @apiSuccess (201) {String} message Result message.
 * @apiSuccess (201) {Object} data The created city.
 *
 * @apiError (422) ValidationError One or more fields are missing or invalid.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 201 Created
 *     {
 *       "message": "City created.",
 *       "data": { "id": 5, "zip_code": 8000, "city": "Székesfehérvár", "population": 95000, "id_county": 2 }
 *     }
 */
Route::post('/city', [CityController::class, 'store'])->name('cities.store');

/**
 * @api {delete} /city/:id Delete city
 * @apiName DestroyCity
 * @apiGroup City
 * @apiDescription Deletes the city if it exists; succeeds silently otherwise.
 *
 * @apiParam {Number} id City id.
 *
 * @apiSuccess {String} message Result message.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     { "message": "City deleted." }
 */
Route::delete('/city/{id}', [CityController::class, 'destroy'])->name('cities.destroy');

/**
 * @api {patch} /city/:id Update city
 * @apiName UpdateCity
 * @apiGroup City
 *
 * @apiParam {Number} id City id.
 * @apiBody {Number} zip_code Zip code.
 * @apiBody {String} city City name.
 * @apiBody {Number} population Population.
 * @apiBody {Number} id_county County id.
 *
 * @apiSuccess {String} message Result message.
 * @apiSuccess {Object} data The updated city.
 *
 * @apiError (404) NotFound City does not exist.
 * @apiError (422) ValidationError One or more fields are missing or invalid.
 *
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {
 *       "message": "City updated.",
 *       "data": { "id": 1, "zip_code": 1011, "city": "Budapest", "population": 1800000, "id_county": 1 }
 *     }
 */
Route::patch('/city/{id}', [CityController::class, 'update'])->name('cities.update');