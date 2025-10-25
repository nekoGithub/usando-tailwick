@extends('layouts.base', ['title' => 'Login'])

@section('css')

@endsection

@section('content')
    <div
        class="bg-cover bg-no-repeat bg-[url('/images/auth-bg.jpg')]'/images/auth-bg-dark.jpg')] min-h-screen flex justify-center items-center">
        <div class="relative">
            <div class="bg-card/70 rounded-lg w-2/3 mx-auto">
                <div class="grid lg:grid-cols-12 grid-cols-1 items-center gap-0">
                    <div class="lg:col-span-5">
                        <div class="text-center px-10 py-12">
                            <div class="text-center">
                                <h4 class="mb-3 text-xl font-semibold text-purple-500">Welcome Back !</h4>
                                <p class="text-base text-default-500">Sign in to continue to
                                    Tailwick.
                                </p>
                            </div>
                            <!-- form -->
                            <form action="{{ route('second', ['dashboards', 'index']) }}" class="text-left w-full mt-10">
                                <div class="mb-4">
                                    <label class="block font-medium text-default-900 text-sm mb-2" for="Username">Username/
                                        Email ID</label>
                                    <input class="form-input" id="Username" placeholder="Enter Username or email"
                                           type="text"/>
                                </div>
                                <div class="mb-4">
                                    <a class="text-primary font-medium text-sm mb-2 float-end"
                                       href="{{ route('second', ['auth', 'boxed-reset-password']) }}">Forgot Password
                                        ?</a>
                                    <label class="block font-medium text-default-900 text-sm mb-2" for="Password">Password</label>
                                    <input class="form-input" id="Password" placeholder="Enter Password" type="text"/>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input class="form-checkbox" id="checkbox-1" type="checkbox"/>
                                    <label class="text-default-900 text-sm font-medium" for="checkbox-1">Remember
                                        Me</label>
                                </div>
                                <div class="mt-10 text-center">
                                    <button class="btn bg-primary text-white w-full" type="button">Sign
                                        In
                                    </button>
                                </div>
                                <div
                                    class="my-9 relative text-center before:absolute before:top-2.5 before:left-0 before:border-t before:border-default-200 before:w-full before:h-0.5 before:right-0 before:-z-0">
                                    <h4 class="relative z-1 py-0.5 px-2 inline-block font-medium bg-card rounded-md">
                                        Sign In with</h4>
                                </div>
                                <div class="flex w-full justify-center items-center gap-2">
                                    <a class="bg-card btn border border-default-200 flex-grow hover:bg-default-150 shadow-sm hover:text-default-800"
                                       href="#">
                                        <i class="iconify-color logos--google-icon"></i>
                                        Use Google
                                    </a>
                                    <a class="bg-card btn border border-default-200 flex-grow hover:bg-default-150 shadow-sm hover:text-default-800"
                                       href="#">
                                        <i class="iconify logos--apple"></i>
                                        Use Apple
                                    </a>
                                </div>
                                <div class="mt-10 text-center">
                                    <p class="text-base text-default-500">Don't have an account ?
                                        <a class="font-semibold underline hover:text-primary transition duration-200"
                                           href="{{ route('second', ['auth', 'boxed-register']) }}"> SignUp</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div
                        class="lg:col-span-7 bg-card/60 mx-2 my-2 shadow-[0_14px_15px_-3px_#f1f5f9,0_4px_6px_-4px_#f1f5f9] rounded-lg">
                        <div class="pt-10 px-10 h-full">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <a href="{{ route('second', ['dashboards', 'index']) }}">
                                        <img alt="logo dark" class="h-6 block" src="/images/logo-dark.png"/>
                                        <img alt="" class="h-6 hidden" src="/images/logo-light.png"/>
                                    </a>
                                </div>
                                <!-- Language Dropdown Button -->
                                <div class="hs-dropdown [--placement:bottom-right] relative inline-flex">
                                    <button aria-expanded="false" aria-haspopup="menu" aria-label="Dropdown"
                                            class="hs-dropdown-toggle py-2 px-4 bg-transparent border border-default-200 text-default-600 hover:border-primary rounded-md hover:text-primary font-medium text-sm gap-2 flex items-center"
                                            type="button">
                                        <img alt="" class="size-5 rounded-full" src="/images/flags/us.jpg"/>
                                        English
                                    </button>
                                    <div aria-labelledby="dropdown-menu" aria-orientation="vertical"
                                         class="hs-dropdown-menu" role="menu">
                                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                                           href="#">
                                            <img alt="" class="size-4 rounded-full" src="/images/flags/us.jpg"/>
                                            English
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                                           href="#">
                                            <img alt="" class="size-4 rounded-full" src="/images/flags/spain.jpg"/>
                                            Spanish
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                                           href="#">
                                            <img alt="" class="size-4 rounded-full" src="/images/flags/germany.jpg"/>
                                            German
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                                           href="#">
                                            <img alt="" class="size-4 rounded-full" src="/images/flags/french.jpg"/>
                                            French
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                                           href="#">
                                            <img alt="" class="size-4 rounded-full" src="/images/flags/japanese.svg"/>
                                            Japanese
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                                           href="#">
                                            <img alt="" class="size-4 rounded-full" src="/images/flags/italy.jpg"/>
                                            Italian
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                                           href="#">
                                            <img alt="" class="size-4 rounded-full" src="/images/flags/russia.jpg"/>
                                            Russian
                                        </a>
                                        <a class="flex items-center gap-x-3.5 py-1.5 font-medium px-3 text-default-600 hover:bg-default-150 rounded"
                                           href="#">
                                            <img alt="" class="size-4 rounded-full" src="/images/flags/arebian.svg"/>
                                            Arabic
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-auto">
                                <img alt="" src="/images/boxed.png"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

@endsection
