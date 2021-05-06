from aiohttp import web
import asyncio, aiohttp, time, random, string, discord

routes = web.RouteTableDef()

async def generateToken(length=22): return ''.join(random.choice(string.ascii_letters) for i in range(length))

@routes.get('/plugins/webhome')
async def get_root(request): return web.json_response({"success": False, "error_message": "Root access to webhome is prohibited."}, status=200, content_type='application/json')

def fixName(user, name):

    fixes = {
        "USERNAME" : user.name,
        "NICKNAME" : user.display_name
    }

    if name is None: return user.name
    for f in list(fixes.keys()): name = name.replace(f, fixes[f])

    return name

def convertUser(bot, person, user):

    roles = []
    if bot.config.json["plugins"]["webhome"]["role_tags"]["show_roles"]:
        for role in user.roles:
            if not user.guild.default_role.id == role.id:
                if not (bot.config.json["plugins"]["webhome"]["role_tags"]["show_uncoloured_roles"] and int(role.color.value) == 0): roles.append({"name": role.name, "colour": str(role.color)})

        roles.reverse() # Reverse for highest first.

    return {
        "name" : fixName(user, person["name"]),
        "id" : person["id"],
        "description" : person["description"],
        "avatar" : str(user.avatar_url),
        "roles" : roles
    }

@routes.get('/plugins/webhome/data')
async def get_data(request):

    bot = request.app["bot"]
    conf = bot.config.json["plugins"]["webhome"]
    data = {}; data["success"] = True

    if conf["shuffle_people"]: random.shuffle(conf["homepage_people"])

    peopleData = []
    guild = await bot.config.getServer(bot, bot.config)

    for person in conf["homepage_people"]:

        user = None
        try:
            user = discord.utils.get(guild.members, id=int(person["id"]))
        except Exception as err:
            pass

        if user is not None: peopleData.append(convertUser(bot, person, user))

    data["default_theme"] = (conf["default_theme"].upper() if conf["default_theme"].upper() in ["DARK", "LIGHT"] else "LIGHT")
    data["button"] = conf["button"]
    data["meta"] = conf["meta"]
    data["people"] = peopleData

    # Add configuration
    for key in ["community_name", "community_tag", "discord_link"]:

        if key == "community_name" and conf[key] is None: data[key] = guild.name # If its none, Use current server name instead.
        else: data[key] = conf[key]

    data["member_count"] = len(guild.members)

    data["custom"] = bot.data["webhome"]["custom"] # Add any custom HTML.
    
    return web.json_response(data, status=200, content_type='application/json')


@routes.get('/plugins/webhome/token/{code}')
async def get_token(request):

    # This is used exclusively for the on site login.
    # It exchanges the code we get back from oauth for an access_token
    # we then simply use the access token to get the ID and log it
    # Basically granting the ID access.

    bot = request.app["bot"]
    code = request.match_info["code"]
    conf = bot.config.json["plugins"]["webhome"]

    data = {
        'client_id': conf["client_id"],
        'client_secret': conf["client_secret"],
        'grant_type': 'authorization_code',
        'code': code,
        'redirect_uri': conf["redirect_uri"],
        'scope': 'identify email connections'
    }
    headers = {
        'Content-Type': 'application/x-www-form-urlencoded'
    }

    out = {"success": False, "error_message": "Could not get token."}
    success = False

    async with aiohttp.ClientSession() as session:
        async with session.post(conf["endpoint"] + "/oauth2/token", data=data, headers=headers) as resp:

            if ((resp.status) == 200):
                success = True
                out = {}
                out = await resp.json()

    if success:
        async with aiohttp.ClientSession() as session:
            async with session.get(conf["endpoint"] + "/users/@me", headers={"Authorization" : ("Bearer " + out["access_token"])}) as resp:

                if ((resp.status) == 200):
                    me = await resp.json()

                    memberInMainServer = None
                    for member in (await bot.config.getServer(bot, bot.config)).members:
                        if str(me["id"]) == str(member.id): memberInMainServer = member

                    if not memberInMainServer is None:

                        token = await generateToken()

                        out = {}
                        out["token"] = token
                        out["success"] = True

                        # Make sure they have permission for login.
                        if not (await bot.utils["managers"]["permissionManager"].allowed("webhome.login", bot, None, memberInMainServer)):
                            # Lacking login permission
                            out = {}
                            out["success"] = False
                            out["error_message"] = "You do not have permission to login. Contact server Administrators for more information."

                        else:
                            bot.data["webhome"]["valid_users"][str(me["id"])] = token

                    else:

                        out = {}
                        out["error_message"] = "Couldn't find user in server."
                        out["success"] = False

                else:
                    out = {}
                    out["error_message"] = "Can not get ID from Token."
                    out["success"] = False

    return web.json_response(out, status=200, content_type='application/json')


@routes.get('/plugins/webhome/info/{token}')
async def get_info(request):

    bot = request.app["bot"]
    conf = bot.config.json["plugins"]["webhome"]
    token = request.match_info["token"]
    page = request.rel_url.query.get("page", None)

    validTokens = {}
    for key in list(bot.data["webhome"]["valid_users"].keys()): validTokens[str(bot.data["webhome"]["valid_users"][str(key)])] = key

    if not token in list(validTokens.keys()):
        # Token is invalid
        return web.json_response({"success": False, "error_message": "Invalid Token"}, status=200, content_type='application/json')

    guild = await bot.config.getServer(bot, bot.config)
    user_id = validTokens[token]

    user = None
    for member in guild.members:
        if str(user_id) == str(member.id): user = member

    if user is None:
        # User not in Discord Server
        return web.json_response({"success": False, "error_message": "You are not in the Discord Server"}, status=200, content_type='application/json')

    return web.json_response(
        {
            "success" : True,
            "id" : user.id,
            "name" : user.name,
            "avatar_url" : str(user.avatar_url),
            "display_name" : user.display_name
        },
        status=200,
        content_type='application/json'
    )

