function searchMovies() {
  $("#movie-list").html(""); // Kosongkan list sebelumnya

  $.ajax({
    url: "https://www.omdbapi.com", // Gunakan https untuk keamanan
    type: "get",
    dataType: "json",
    data: {
      apikey: "f45a0831", // API key Anda
      s: $("#search-input").val(), // Ambil nilai dari input
    },
    success: function (res) {
      console.log(res); // Debugging untuk melihat response
      if (res.Response === "True") {
        let movies = res.Search;

        // Iterasi setiap movie dalam hasil pencarian
        $.each(movies, function (i, data) {
          $("#movie-list").append(`
              <div class="col-md-4">
                <div class="card">
                  <img src="${data.Poster}" class="card-img-top" alt="Poster ${data.Title}">
                  <div class="card-body">
                    <h5 class="card-title">${data.Title}</h5>
                    <p class="card-text">Tahun: ${data.Year}</p>
                    <a href="#" class="btn btn-primary">Detail</a>
                  </div>
                </div>
              </div>
            `);
        });
      } else {
        // Jika tidak ditemukan, tampilkan error dari API
        $("#movie-list").html(
          `<h1 class="text-center text-danger">${res.Error}</h1>`
        );
      }
    },
    error: function (err) {
      // Tangani jika terjadi error dari server
      console.error("API Error: ", err);
      $("#movie-list").html(
        `<h1 class="text-center text-danger">Terjadi kesalahan pada server.</h1>`
      );
    },
  });
}

$("button").on("click", function () {
  searchMovies();
});
$("#search-input").on("keyup", function (e) {
  if (e.key === "Enter") {
    searchMovies();
  }
});
