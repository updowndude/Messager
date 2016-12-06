<%@ Page Language="C#" AutoEventWireup="true" CodeBehind="post.aspx.cs" MasterPageFile="~/layout.Master" Inherits="Messenger.pages.post" %>
<asp:Content ID="postsTitle" ContentPlaceHolderID="title" runat="server">
   <link rel="stylesheet" type="text/css" href="../public/dist/myStyle.css" />
   <!-- links to rescoures -->
   <link rel="icon", type="image/x-icon", href="../public/images/favicon.ico" />
   <link rel="shortcut icon", type="image/x-icon", href="../public/images/favicon.ico" />
   <title>Groups</title>
</asp:Content>
<asp:Content ID="postsPage" ContentPlaceHolderID="body" runat="server">
   <main>
      <form id="postsForm" runat="server">
         <nav class="navbar navbar-light bg-faded">
            <ul class="nav navbar-nav">
               <li class="nav-item active">
                  <asp:LinkButton ID="btnHome" CssClass="btn btn-primary" runat="server" OnClick="btnHome_Click">
                     <span class="glyphicon glyphicon-home"></span>
                  </asp:LinkButton>
               </li>
               <li class="nav-item active">
                  <a class="nav-link" href="#Posts">Posts</a>
               </li>
               <li class="nav-item active">
                  <a class="nav-link" href="#Add">
                  <span class="glyphicon glyphicon-plus-sign"></span>
                  </a>
               </li>
               <li class="nav-item active">
                  <a class="nav-link" href="#Members">Members</a>
               </li>
               <li class="nav-item active">
                  <a class="nav-link" href="#Data">Data</a>
               </li>
            </ul>
         </nav>
         <section>
            <asp:SqlDataSource ID="sqlPosts" runat="server" ConnectionString='<%$ ConnectionStrings:Messenger2 %>' ProviderName='<%$ ConnectionStrings:Messenger2.ProviderName %>' SelectCommand="select * FROM ((person INNER JOIN people_group ON person.person_id = people_group.person_id) INNER JOIN groups ON people_group.groups_id = groups.groups_id) where [name] = ? order by posted">
               <SelectParameters>
                  <asp:SessionParameter Name="name" SessionField="name" Type="String" />
               </SelectParameters>
            </asp:SqlDataSource>
            <article id="Posts">
               <asp:DataList ID="dlistPosts" runat="server" DataSourceID="sqlPosts">
                  <ItemTemplate>
                     <div class="card">
                        <div class="card-block">
                           <h3 class="card-title">
                              <asp:Label Text='<%# Eval("fName"); Eval("lName"); %>' runat="server" id="lblFullName"/>
                           </h3>
                           <asp:Label Text='<%# Eval("message") %>' runat="server" id="lblMessage"/>
                        </div>
                        <div class="card-block">
                           <asp:Label Text='<%# Eval("posted") %>' runat="server" id="lblPosted"/>
                        </div>
                     </div>
                  </ItemTemplate>
               </asp:DataList>
            </article>
            <article id="Add">
            </article>
            <article id="Members">
            </article>
            <article id="Data">
            </article>
         </section>
      </form>
   </main>
   <script src="../public/dist/my-com.js" type="text/javascript"></script>
</asp:Content>