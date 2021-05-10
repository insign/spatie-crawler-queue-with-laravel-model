<!DOCTYPE html>
<html>
  <head>
    {{-- Custom styles & script --}}
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script async src="{{ mix('js/app.js') }}"></script>
  </head>
  <body class="px-10 py-5">
    <form action="/" method="GET">
      <div class="pb-5 border-b border-gray-200 space-y-3 sm:flex sm:items-center sm:justify-between sm:space-x-4 sm:space-y-0">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
          URL: <input
            name="url"
            value="{{ $url }}"
            placeholder="https://"
            class="bg-gray-300 w-96 rounded-md py-2 px-4 text-base leading-6 text-gray-900"
          >
        </h3>
        <div class="flex space-x-3">
          <span class="shadow-sm rounded-md">
            <input
              type="submit"
              class="inline-flex items-center px-4 py-2 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:shadow-outline-indigo focus:border-indigo-700 active:bg-indigo-700 transition duration-150 ease-in-out"
              value="Crawl"
            >
          </span>
        </div>
      </div>
    </form>

    <pre>{{ $log }}</pre>
  </body>
</html>
